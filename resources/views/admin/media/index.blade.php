<x-app-layout :page="__('Media')" layout="admin">

    <x-slot name="pretitle">{{ __('Media') }}</x-slot>
    <x-slot name="subtitle">{{ __('Manage Media') }}</x-slot>

    <x-slot name="actions">
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createFolderModal">
            <i class="ti ti-folder-plus icon icon-1"></i>
            {{ __('Create Folder') }}
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="ti ti-plus icon icon-1"></i>
            {{ __('Upload Media') }}
        </button>
    </x-slot>

    <x-common.alert />

    <div class="card">
        <div class="card-table">
            <div class="card-header">
                <div class="row w-full">
                    <div class="col">
                        <h3 class="card-title mb-0">{{ __('Media') }}</h3>
                        <p class="text-secondary m-0">{{ __('Manage Media') }}</p>
                    </div>
                    <div class="col-md-auto col-sm-12">
                        <div class="ms-auto d-flex flex-wrap btn-list">
                            <form action="{{ url()->current() }}" method="get"
                                class="input-group input-group-flat w-auto">
                                <span class="input-group-text">
                                    <i class="ti ti-search icon icon-1"></i>
                                </span>
                                <input id="advanced-table-search" type="text" class="form-control" autocomplete="off"
                                    placeholder="{{ __('Search') }}" name="search" value="{{ request('search') }}">
                                <span class="input-group-text">
                                    <kbd>ctrl + K</kbd>
                                </span>
                            </form>
                            <div class="btn-group bulk-actions" role="group" style="display: none;">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="bulkMoveBtn"
                                    title="Move Selected">
                                    <i class="ti ti-arrow-right icon icon-1"></i>
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm" id="bulkCopyBtn"
                                    title="Copy Selected">
                                    <i class="ti ti-copy icon icon-1"></i>
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" id="bulkDownloadBtn"
                                    title="Download Selected">
                                    <i class="ti ti-download icon icon-1"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn"
                                    title="Delete Selected">
                                    <i class="ti ti-trash icon icon-1"></i>
                                </button>
                            </div>
                            <span class="selected-count text-muted small" style="display: none;" id="selectedCount">0
                                selected</span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="card-body" class="p-4">

                <div class="row" style="height: 600px;">

                    <!-- Main Content Area -->
                    <div class="col h-100 d-flex flex-column p-0 transition-all" id="mainContent">

                        <!-- Toolbar -->
                        <div class="d-flex align-items-center justify-content-between p-3 border-bottom bg-white">
                            <nav aria-label="breadcrumb" class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="backBtn"
                                    style="display: none;" title="Go Back">
                                    <i class="ti ti-arrow-left icon icon-1"></i>
                                </button>
                                <div class="form-check ms-2">
                                    <input class="form-check-input" type="checkbox" id="selectAllCheckbox"
                                        title="Select All">
                                </div>
                                <ol class="breadcrumb mb-0" id="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="#"
                                            onclick="navigateToFolder(null); return false;">{{ __('Home') }}</a>
                                    </li>
                                </ol>
                            </nav>
                            <div class="d-flex gap-2">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm active"
                                        id="viewGrid">
                                        <i class="ti ti-layout-2 icon icon-1"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="viewList">
                                        <i class="ti ti-list icon icon-1"></i>
                                    </button>
                                </div>
                                <button class="btn btn-outline-secondary btn-sm" id="toggleInfoBtn"
                                    title="Toggle Details">
                                    <i class="ti ti-columns-2 icon icon-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- File Grid/List -->
                        <div class="flex-grow-1 overflow-auto p-3 bg-light" id="fileContainer">
                            <!-- Files injected by JS -->
                        </div>

                        <!-- Footer Status -->
                        <div class="bg-white border-top p-2 px-3 small text-muted d-flex justify-content-between">
                            <span id="itemCount">0 items</span>
                            <span id="storageStatus">1.2 GB of 15 GB used</span>
                        </div>
                    </div>

                    <!-- Right Sidebar (Details & Actions) -->
                    <div class="col-md-3 bg-white border-start h-100 overflow-auto p-0 transition-all"
                        id="rightSidebar" style="display: none;">
                        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold">Details</h6>
                            <button type="button" class="btn-close text-reset" id="closeInfoBtn"
                                aria-label="Close"></button>
                        </div>

                        <div id="emptySelection" class="text-center p-5 text-muted">
                            <i class="ti ti-cursor fs-1 mb-3 d-block"></i>
                            <p>Select an item to view details</p>
                        </div>

                        <div id="fileDetails" class="d-none">
                            <!-- Preview -->
                            <div class="p-4 text-center bg-light border-bottom">
                                <div id="previewIcon" class="mb-3">
                                    <i class="ti ti-file fs-1 text-secondary"></i>
                                </div>
                                <h6 class="text-break mb-1" id="detailName">filename.txt</h6>
                                <span class="badge bg-secondary text-white" id="detailType">TXT</span>
                            </div>

                            <!-- Actions -->
                            <div class="p-3 border-bottom">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-sm" id="actionOpen"><i
                                            class="ti ti-arrow-up-right me-2"></i> Open</button>
                                    <div class="row g-2">
                                        <div class="col">
                                            <button class="btn btn-outline-secondary btn-sm w-100" id="actionRename"
                                                data-bs-toggle="modal" data-bs-target="#renameModal"><i
                                                    class="ti ti-pencil"></i> Rename</button>
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-outline-danger btn-sm w-100" id="actionDelete"><i
                                                    class="ti ti-trash"></i> Delete</button>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-primary btn-sm" id="actionDownload"><i
                                            class="ti ti-download me-2"></i> Download</button>
                                    <div class="row g-2 mt-2">
                                        <div class="col">
                                            <button class="btn btn-outline-info btn-sm w-100" id="actionMove"><i
                                                    class="ti ti-arrow-right me-1"></i> Move</button>
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-outline-info btn-sm w-100" id="actionCopy"><i
                                                    class="ti ti-copy me-1"></i> Copy</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Info List -->
                            <div class="p-3">
                                <h6 class="text-muted text-uppercase small fw-bold mb-3">Information</h6>
                                <div class="mb-3">
                                    <label class="small text-muted d-block">Type</label>
                                    <span class="small fw-bold" id="infoType">Text File</span>
                                </div>
                                <div class="mb-3">
                                    <label class="small text-muted d-block">Size</label>
                                    <span class="small fw-bold" id="infoSize">24 KB</span>
                                </div>
                                <div class="mb-3">
                                    <label class="small text-muted d-block">Location</label>
                                    <span class="small fw-bold" id="infoLocation">My Drive/Documents</span>
                                </div>
                                <div class="mb-3">
                                    <label class="small text-muted d-block">Modified</label>
                                    <span class="small fw-bold" id="infoDate">Oct 24, 2023</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Rename Modal -->
    <div class="modal fade" id="renameModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rename Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" id="renameInput">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmRename">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Files</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-5 border border-dashed m-3 rounded bg-light">
                    <i class="ti ti-cloud-upload fs-1 text-primary mb-3"></i>
                    <p class="mb-0">Drag and drop files here or click to browse</p>
                    <input type="file" class="d-none" id="fileInput" multiple>
                    <button class="btn btn-outline-primary mt-3"
                        onclick="document.getElementById('fileInput').click()">Browse Files</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmUpload">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Folder Modal -->
    <div class="modal fade" id="createFolderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Folder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="folderNameInput" class="form-label">Folder Name</label>
                    <input type="text" class="form-control" id="folderNameInput" placeholder="Enter folder name">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmCreateFolder">Create</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Folder Selector Modal -->
    <div class="modal fade" id="folderSelector" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="folderSelectorTitle">Select Destination Folder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="folderSelectorBody" style="max-height: 400px; overflow-y: auto;">
                    <!-- Folder tree will be populated by JS -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div id="mediaConfig" class="d-none" data-list-url="{{ route('admin.media.list') }}"
        data-upload-url="{{ route('admin.media.store') }}"
        data-resource-template="{{ str_replace('/1', '/:mediaId', route('admin.media.update', 1)) }}">
    </div>

    <style>
        /* Sidebar Tree */
        .folder-tree-item {
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            color: #495057;
            text-decoration: none;
            display: block;
            transition: background-color 0.2s;
        }

        .folder-tree-item:hover {
            background-color: #e9ecef;
            color: #0d6efd;
        }

        /* File Grid Items */
        .file-item {
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .file-item:hover {
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        .file-item.selected {
            background-color: #e7f1ff;
            border-color: #cff4fc;
        }

        .file-icon {
            font-size: 3rem;
            line-height: 1;
        }

        .file-name {
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Right Sidebar Transition */
        #rightSidebar {
            width: 300px;
            flex-shrink: 0;
            transition: margin-right 0.3s ease-in-out;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            #rightSidebar {
                position: fixed;
                right: 0;
                top: 60px;
                bottom: 0;
                z-index: 1000;
                box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
            }
        }
    </style>

</x-app-layout>
