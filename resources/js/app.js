import './bootstrap';

const axiosInstance = window.axios;
const bootstrap = window.bootstrap;

const fileContainer = document.getElementById("fileContainer");
const breadcrumb = document.getElementById("breadcrumb");
const rightSidebar = document.getElementById("rightSidebar");
const toggleInfoBtn = document.getElementById("toggleInfoBtn");
const closeInfoBtn = document.getElementById("closeInfoBtn");
const backBtn = document.getElementById("backBtn");
const searchInput = document.getElementById("searchInput") ?? document.getElementById("advanced-table-search");
const fileInput = document.getElementById("fileInput");
const uploadModalEl = document.getElementById("uploadModal");
const renameModalEl = document.getElementById("renameModal");
const mediaConfigEl = document.getElementById("mediaConfig");

const mediaRoutes = {
    list: "/admin/media/list",
    upload: "/admin/media",
    folders: "/admin/media/folders",
    resourceTemplate: "/admin/media/:mediaId",
};

let mediaFiles = [];
let selectedItemId = null;
let selectedItems = new Set();
let currentFolderId = null;
let currentFolder = null;
let viewMode = "grid";
let isInfoOpen = true;
let mediaMeta = {
    used_bytes: 0,
    storage_limit_bytes: 15 * 1024 * 1024 * 1024,
};

if (mediaConfigEl) {
    mediaRoutes.list = mediaConfigEl.dataset.listUrl ?? mediaRoutes.list;
    mediaRoutes.upload = mediaConfigEl.dataset.uploadUrl ?? mediaRoutes.upload;
    mediaRoutes.resourceTemplate = mediaConfigEl.dataset.resourceTemplate ?? mediaRoutes.resourceTemplate;
}

const getIcon = (type) => {
    switch (type) {
        case "folder":
            return '<i class="ti ti-folder text-warning"></i>';
        case "image":
            return '<i class="ti ti-photo text-primary"></i>';
        case "pdf":
            return '<i class="ti ti-file-type-pdf text-danger"></i>';
        case "txt":
            return '<i class="ti ti-file-type-text text-secondary"></i>';
        case "excel":
            return '<i class="ti ti-file-type-excel text-success"></i>';
        case "ppt":
            return '<i class="ti ti fi text-warning"></i>';
        default:
            return '<i class="ti ti-file-type-unknown text-secondary"></i>';
    }
};

document.addEventListener("DOMContentLoaded", () => {
    renderBreadcrumbs();
    loadMedia();
    updateInfoPanel();

    document.getElementById("viewGrid")?.addEventListener("click", () => setView("grid"));
    document.getElementById("viewList")?.addEventListener("click", () => setView("list"));

    toggleInfoBtn?.addEventListener("click", toggleInfoPanel);
    closeInfoBtn?.addEventListener("click", toggleInfoPanel);
    backBtn?.addEventListener("click", navigateBack);

    document.getElementById("actionDelete")?.addEventListener("click", deleteSelectedItem);
    document.getElementById("actionOpen")?.addEventListener("click", openSelectedItem);
    document.getElementById("actionDownload")?.addEventListener("click", downloadSelectedItem);
    document.getElementById("confirmRename")?.addEventListener("click", renameSelectedItem);
    document.getElementById("confirmUpload")?.addEventListener("click", uploadFile);
    document.getElementById("createFolderBtn")?.addEventListener("click", () => createFolder());
    document.getElementById("confirmCreateFolder")?.addEventListener("click", createFolder);
    document.getElementById("actionMove")?.addEventListener("click", () => moveFile());
    document.getElementById("actionCopy")?.addEventListener("click", () => copyFile());

    document.getElementById("selectAllCheckbox")?.addEventListener("change", toggleSelectAll);
    document.getElementById("bulkMoveBtn")?.addEventListener("click", () => bulkMove());
    document.getElementById("bulkCopyBtn")?.addEventListener("click", () => bulkCopy());
    document.getElementById("bulkDownloadBtn")?.addEventListener("click", () => bulkDownload());
    document.getElementById("bulkDeleteBtn")?.addEventListener("click", () => bulkDelete());

    if (searchInput) {
        let searchTimer;
        searchInput.addEventListener("input", (e) => {
            clearTimeout(searchTimer);
            const term = e.target.value.trim();
            searchTimer = setTimeout(() => loadMedia(term), 300);
        });
    }
});

function setView(mode) {
    viewMode = mode;
    document.getElementById("viewGrid")?.classList.toggle("active", mode === "grid");
    document.getElementById("viewList")?.classList.toggle("active", mode === "list");
    renderFiles();
}

async function loadMedia(search = "") {
    if (!axiosInstance) {
        console.warn("Axios is not available; cannot load media.");
        return;
    }

    try {
        const params = {};
        if (search) {
            params.search = search;
        }
        if (currentFolderId) {
            params.parent_id = currentFolderId;
            currentFolder = await getFolderById(currentFolderId);
        } else {
            params.parent_id = null;
            currentFolder = null;
        }

        const response = await axiosInstance.get(mediaRoutes.list, { params });

        mediaFiles = response.data.data ?? [];
        const meta = response.data.meta ?? {};
        mediaMeta.used_bytes = meta.used_bytes ?? mediaMeta.used_bytes;
        mediaMeta.storage_limit_bytes = meta.storage_limit_bytes ?? mediaMeta.storage_limit_bytes;

        if (!mediaFiles.find((item) => item.id === selectedItemId)) {
            selectedItemId = null;
        }

        // Clear selections for items that no longer exist
        selectedItems.forEach(id => {
            if (!mediaFiles.find(item => item.id === id)) {
                selectedItems.delete(id);
            }
        });

        renderFiles();
        renderBreadcrumbs();
        updateBackButton();
        updateBulkActions();
        updateInfoPanel();
    } catch (error) {
        console.error("Unable to load media", error);
    }
}

async function getFolderById(folderId) {
    if (!axiosInstance || !folderId) {
        return null;
    }

    try {
        const response = await axiosInstance.get(mediaRoutes.folders);
        const folders = response.data.data ?? [];
        return folders.find(f => f.id === folderId) || null;
    } catch (error) {
        console.error("Unable to get folder", error);
        return null;
    }
}

function renderFiles() {
    renderFileList(mediaFiles);
    updateStorageStatus();
}

function updateStorageStatus() {
    const counter = document.getElementById("itemCount");
    if (counter) {
        counter.textContent = `${mediaFiles.length} items`;
    }

    const status = document.getElementById("storageStatus");
    if (status) {
        status.textContent = `${formatBytes(mediaMeta.used_bytes)} of ${formatBytes(mediaMeta.storage_limit_bytes)} used`;
    }
}

function renderFileList(files) {
    if (!fileContainer) {
        return;
    }

    fileContainer.innerHTML = "";

    if (files.length === 0) {
        fileContainer.innerHTML = `
                <div class="text-center mt-5 text-muted">
                    <i class="ti ti-folder-open display-1"></i>
                    <p class="mt-3">This folder is empty</p>
                </div>
            `;
        return;
    }

    if (viewMode === "grid") {
        const row = document.createElement("div");
        row.className = "row g-3";

        files.forEach((file) => {
            const col = document.createElement("div");
            col.className = "col-6 col-md-4 col-lg-3 col-xl-2";
            const card = document.createElement("div");
            const isChecked = selectedItems.has(file.id);
            card.className = `card h-100 file-item shadow-sm position-relative ${selectedItemId === file.id ? "selected" : ""} ${isChecked ? "bg-light" : ""}`;

            const checkbox = `<div class="position-absolute top-0 start-0 m-2">
                <input type="checkbox" class="form-check-input media-checkbox" data-item-id="${file.id}" ${isChecked ? "checked" : ""}>
            </div>`;

            const displayContent = file.type === "image" && file.thumbnail
                ? `<div class="card-body p-2 text-center">
                    ${checkbox}
                    <img src="${file.thumbnail}" alt="${file.name}" class="img-fluid rounded mb-2" style="max-height: 120px; width: 100%; object-fit: cover;" onerror="this.style.display='none'; this.parentElement.querySelector('.file-icon').style.display='block';">
                    <div class="file-icon mb-2 d-none">${getIcon(file.type)}</div>
                    <div class="file-name text-dark small" title="${file.name}">${file.name}</div>
                </div>`
                : `<div class="card-body text-center">
                    ${checkbox}
                    <div class="file-icon mb-2">${getIcon(file.type)}</div>
                    <div class="file-name text-dark" title="${file.name}">${file.name}</div>
                </div>`;

            card.innerHTML = displayContent;

            const checkboxEl = card.querySelector('.media-checkbox');
            checkboxEl.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleItemSelection(file.id);
            });

            card.addEventListener("click", (e) => {
                if (e.target.type === 'checkbox') return;
                if (file.type === 'folder') {
                    navigateToFolder(file.id);
                } else {
                    selectItem(file.id, true);
                }
            });
            card.addEventListener("dblclick", () => {
                if (file.type === 'folder') {
                    navigateToFolder(file.id);
                } else {
                    openItem(file.id);
                }
            });
            col.appendChild(card);
            row.appendChild(col);
        });

        fileContainer.appendChild(row);
    } else {
        const list = document.createElement("div");
        list.className = "list-group list-group-flush";
        list.innerHTML = `
            <div class="list-group-item bg-light fw-bold text-muted border-bottom">
                <div class="row">
                    <div class="col-6">Name</div>
                    <div class="col-3">Date Modified</div>
                    <div class="col-3">Size</div>
                </div>
            </div>
        `;

        files.forEach((file) => {
            const item = document.createElement("div");
            const isChecked = selectedItems.has(file.id);
            item.className = `list-group-item list-group-item-action file-item ${selectedItemId === file.id ? "selected" : ""} ${isChecked ? "bg-light" : ""}`;

            const iconContent = file.type === "image" && file.thumbnail
                ? `<img src="${file.thumbnail}" alt="${file.name}" class="me-3 rounded" style="width: 40px; height: 40px; object-fit: cover;" onerror="this.style.display='none'; this.parentElement.querySelector('.file-icon-fallback').style.display='inline-block';">
                   <span class="file-icon-fallback me-3 fs-5 d-none">${getIcon(file.type)}</span>`
                : `<span class="me-3 fs-5">${getIcon(file.type)}</span>`;

            item.innerHTML = `
                <div class="row align-items-center">
                    <div class="col-auto">
                        <input type="checkbox" class="form-check-input media-checkbox" data-item-id="${file.id}" ${isChecked ? "checked" : ""}>
                    </div>
                    <div class="col d-flex align-items-center">
                        ${iconContent}
                        <span class="text-truncate">${file.name}</span>
                    </div>
                    <div class="col-3 small text-muted">${file.date}</div>
                    <div class="col-3 small text-muted">${file.size}</div>
                </div>
            `;

            const checkboxEl = item.querySelector('.media-checkbox');
            checkboxEl.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleItemSelection(file.id);
            });

            item.addEventListener("click", (e) => {
                if (e.target.type === 'checkbox') return;
                if (file.type === 'folder') {
                    navigateToFolder(file.id);
                } else {
                    selectItem(file.id, true);
                }
            });
            item.addEventListener("dblclick", () => {
                if (file.type === 'folder') {
                    navigateToFolder(file.id);
                } else {
                    openItem(file.id);
                }
            });
            list.appendChild(item);
        });

        fileContainer.appendChild(list);
    }
}

async function renderBreadcrumbs() {
    if (!breadcrumb) {
        return;
    }

    breadcrumb.innerHTML = `<li class="breadcrumb-item"><a href="#" onclick="navigateToFolder(null); return false;">Home</a></li>`;

    if (currentFolder && currentFolder.parent_id) {
        await buildBreadcrumbPath(currentFolder);
    } else if (currentFolder) {
        const li = document.createElement("li");
        li.className = "breadcrumb-item active";
        li.setAttribute("aria-current", "page");
        li.textContent = currentFolder.name;
        breadcrumb.appendChild(li);
    }
}

async function buildBreadcrumbPath(folder) {
    if (!axiosInstance) {
        return;
    }

    try {
        const response = await axiosInstance.get(mediaRoutes.folders);
        const allFolders = response.data.data ?? [];
        const folderMap = new Map(allFolders.map(f => [f.id, f]));

        const path = [];
        let current = folder;

        while (current && current.parent_id) {
            path.unshift(current);
            current = folderMap.get(current.parent_id);
        }

        path.forEach((folderItem, index) => {
            const li = document.createElement("li");
            const isLast = index === path.length - 1;

            if (isLast) {
                li.className = "breadcrumb-item active";
                li.setAttribute("aria-current", "page");
                li.textContent = folderItem.name;
            } else {
                li.className = "breadcrumb-item";
                const link = document.createElement("a");
                link.href = "#";
                link.onclick = (e) => {
                    e.preventDefault();
                    navigateToFolder(folderItem.id);
                    return false;
                };
                link.textContent = folderItem.name;
                li.appendChild(link);
            }

            breadcrumb.appendChild(li);
        });
    } catch (error) {
        console.error("Unable to build breadcrumb path", error);
    }
}

function navigateToFolder(folderId) {
    currentFolderId = folderId;
    selectedItemId = null;
    loadMedia();
}

function navigateBack() {
    if (currentFolder && currentFolder.parent_id) {
        navigateToFolder(currentFolder.parent_id);
    } else {
        navigateToFolder(null);
    }
}

function updateBackButton() {
    if (!backBtn) {
        return;
    }

    if (currentFolderId) {
        backBtn.style.display = "inline-block";
        backBtn.disabled = false;
    } else {
        backBtn.style.display = "none";
        backBtn.disabled = true;
    }
}

function getFile(id) {
    return mediaFiles.find((file) => file.id === id);
}

function selectItem(id, autoOpen = false) {
    selectedItemId = id;
    renderFiles();
    updateInfoPanel();

    if ((autoOpen || !isInfoOpen) && rightSidebar?.style.display === "none") {
        toggleInfoPanel();
    }
}

function openItem(id) {
    const file = getFile(id);
    if (!file || !file.url) {
        return;
    }

    window.open(file.url, "_blank");
}

function openSelectedItem() {
    if (selectedItemId) {
        openItem(selectedItemId);
    }
}

function downloadSelectedItem() {
    if (!selectedItemId) {
        return;
    }

    const file = getFile(selectedItemId);
    if (!file || !file.url) {
        return;
    }

    const link = document.createElement("a");
    link.href = file.url;
    link.download = file.name;
    document.body.appendChild(link);
    link.click();
    link.remove();
}

function toggleInfoPanel() {
    isInfoOpen = !isInfoOpen;
    if (!rightSidebar) {
        return;
    }

    rightSidebar.style.display = isInfoOpen ? "block" : "none";
}

async function deleteSelectedItem() {
    if (!selectedItemId || !axiosInstance) {
        return;
    }

    const file = getFile(selectedItemId);
    if (!file) {
        return;
    }

    const itemType = file.type === 'folder' ? 'folder' : 'file';
    if (!confirm(`Are you sure you want to delete this ${itemType}?`)) {
        return;
    }

    const deleteUrl = mediaRoutes.resourceTemplate.replace(":mediaId", selectedItemId);
    try {
        const response = await axiosInstance.delete(deleteUrl);
        selectedItemId = null;
        selectedItems.delete(selectedItemId);
        await loadMedia();
        updateBulkActions();
        updateInfoPanel();
    } catch (error) {
        console.error("Unable to delete media", error);
        const errorMsg = error.response?.data?.error || error.response?.data?.message || "Unable to delete this item.";
        alert(errorMsg);
    }
}

async function renameSelectedItem() {
    if (!selectedItemId || !axiosInstance) {
        return;
    }

    const nameInput = document.getElementById("renameInput");
    if (!nameInput) {
        return;
    }

    const newName = nameInput.value.trim();
    if (!newName) {
        alert("Please enter a name.");
        return;
    }

    const file = getFile(selectedItemId);
    if (!file) {
        return;
    }

    // Check if name is the same
    if (newName === file.name) {
        const modal = bootstrap?.Modal?.getInstance(renameModalEl);
        modal?.hide();
        return;
    }

    const updateUrl = mediaRoutes.resourceTemplate.replace(":mediaId", selectedItemId);
    try {
        await axiosInstance.patch(updateUrl, { name: newName });
        const modal = bootstrap?.Modal?.getInstance(renameModalEl);
        modal?.hide();
        await loadMedia();
        updateInfoPanel();
    } catch (error) {
        console.error("Unable to rename media", error);
        const errorMsg = error.response?.data?.error || error.response?.data?.message || "Unable to rename this item.";
        alert(errorMsg);
    }
}

if (renameModalEl) {
    renameModalEl.addEventListener("show.bs.modal", () => {
        if (!selectedItemId) {
            return;
        }
        const file = getFile(selectedItemId);
        if (file) {
            document.getElementById("renameInput").value = file.name;
        }
    });
}

async function uploadFile() {
    if (!fileInput || !axiosInstance) {
        return;
    }

    const files = fileInput.files;
    if (!files || files.length === 0) {
        alert("Please select at least one file.");
        return;
    }

    const formData = new FormData();
    Array.from(files).forEach((file) => formData.append("files[]", file));

    // Always include parent_id (null if at root, currentFolderId if in a folder)
    formData.append("parent_id", currentFolderId || "");

    try {
        await axiosInstance.post(mediaRoutes.upload, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        const modal = bootstrap?.Modal?.getInstance(uploadModalEl);
        modal?.hide();
        fileInput.value = "";
        await loadMedia();
    } catch (error) {
        console.error("Unable to upload media", error);
        alert("Unable to upload files.");
    }
}

async function createFolder() {
    const folderNameInput = document.getElementById("folderNameInput");
    if (!folderNameInput || !axiosInstance) {
        return;
    }

    const folderName = folderNameInput.value.trim();
    if (!folderName) {
        alert("Please enter a folder name.");
        return;
    }

    try {
        const formData = new FormData();
        formData.append("name", folderName);

        // Always include parent_id (null/empty if at root, currentFolderId if in a folder)
        formData.append("parent_id", currentFolderId || "");

        await axiosInstance.post(mediaRoutes.upload, formData);
        const modal = bootstrap?.Modal?.getInstance(document.getElementById("createFolderModal"));
        modal?.hide();
        folderNameInput.value = "";
        await loadMedia();
    } catch (error) {
        console.error("Unable to create folder", error);
        alert("Unable to create folder.");
    }
}

let currentMoveAction = null;
let currentMoveItems = [];

async function moveFile() {
    if (!selectedItemId || !axiosInstance) {
        return;
    }
    currentMoveAction = 'move';
    await showFolderSelector();
}

async function copyFile() {
    if (!selectedItemId || !axiosInstance) {
        return;
    }
    currentMoveAction = 'copy';
    await showFolderSelector();
}

async function showFolderSelector() {
    if (!axiosInstance) {
        return;
    }

    try {
        const response = await axiosInstance.get(mediaRoutes.folders);
        const folders = response.data.data ?? [];
        const file = getFile(selectedItemId);

        if (!file) {
            return;
        }

        const folderSelectorEl = document.getElementById("folderSelector");
        const folderSelectorBody = document.getElementById("folderSelectorBody");
        if (!folderSelectorEl || !folderSelectorBody) {
            return;
        }

        const isBulk = currentMoveAction?.startsWith('bulk-');
        const items = isBulk ? currentMoveItems : [selectedItemId];

        const excludedIds = items.filter(id => {
            const item = getFile(id);
            return item && item.type === 'folder';
        }).map(id => {
            const item = getFile(id);
            return item?.id;
        }).filter(Boolean);

        const currentParentId = isBulk ? null : (file?.parent_id || null);

        const actionText = isBulk
            ? (currentMoveAction.includes('move') ? 'Move' : 'Copy') + ' Selected Items'
            : (currentMoveAction === 'move' ? 'Move' : 'Copy');

        if (file) {
            document.getElementById("folderSelectorTitle").textContent = `${actionText} "${file.name}" to:`;
        } else {
            document.getElementById("folderSelectorTitle").textContent = `${actionText} to:`;
        }

        folderSelectorBody.innerHTML = `
            <div class="list-group">
                <button type="button" class="list-group-item list-group-item-action" data-folder-id="null">
                    <i class="ti ti-home me-2"></i> Root (Home)
                </button>
                ${buildFolderTree(folders, currentParentId, excludedIds)}
            </div>
        `;

        let modal = bootstrap?.Modal?.getInstance(folderSelectorEl);
        if (!modal) {
            modal = new bootstrap.Modal(folderSelectorEl);
        }
        modal.show();

        folderSelectorBody.querySelectorAll('[data-folder-id]').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const folderId = e.currentTarget.dataset.folderId;
                await performMoveAction(folderId === 'null' ? null : parseInt(folderId));
                modal.hide();
            });
        });
    } catch (error) {
        console.error("Unable to load folders", error);
        alert("Unable to load folders.");
    }
}

function buildFolderTree(folders, currentParentId, excludeIds = []) {
    const excludeSet = new Set(Array.isArray(excludeIds) ? excludeIds : [excludeIds]);
    const rootFolders = folders.filter(f => !f.parent_id && !excludeSet.has(f.id));
    let html = '';

    rootFolders.forEach(folder => {
        html += buildFolderItem(folder, folders, 0, currentParentId, excludeSet);
    });

    return html;
}

function buildFolderItem(folder, allFolders, depth, currentParentId, excludeSet) {
    const indent = depth * 20;
    const isSelected = folder.id === currentParentId;
    const children = allFolders.filter(f => f.parent_id === folder.id && !excludeSet.has(f.id));

    let html = `
        <button type="button" class="list-group-item list-group-item-action ${isSelected ? 'active' : ''}" 
                data-folder-id="${folder.id}" style="padding-left: ${indent + 12}px;">
            <i class="ti ti-folder me-2"></i> ${folder.name}
        </button>
    `;

    children.forEach(child => {
        html += buildFolderItem(child, allFolders, depth + 1, currentParentId, excludeSet);
    });

    return html;
}

async function performMoveAction(folderId) {
    if (!axiosInstance) {
        return;
    }

    const items = currentMoveAction?.startsWith('bulk-') ? currentMoveItems : [selectedItemId];
    const isBulk = currentMoveAction?.startsWith('bulk-');
    const action = isBulk ? currentMoveAction.replace('bulk-', '') : currentMoveAction;

    if (items.length === 0 || !items[0]) {
        return;
    }

    // Validate folder moves - check if trying to move into itself or descendants
    for (const itemId of items) {
        const file = getFile(itemId);
        if (!file) continue;

        if (file.type === 'folder') {
            if (folderId && parseInt(folderId) === file.id) {
                alert("Cannot move folder into itself.");
                return;
            }

            // Check if trying to move into a subfolder (would need recursive check, but basic validation here)
            if (folderId && parseInt(folderId) === file.id) {
                alert("Cannot move folder into itself.");
                return;
            }
        }
    }

    try {
        const promises = items.map(async (itemId) => {
            const updateUrl = mediaRoutes.resourceTemplate.replace(":mediaId", itemId);
            const response = await axiosInstance.patch(updateUrl, {
                action: action,
                parent_id: folderId === null || folderId === 'null' ? null : folderId,
            });
            return response;
        });

        await Promise.all(promises);

        if (isBulk) {
            selectedItems.clear();
        } else {
            selectedItemId = null;
        }

        await loadMedia();
        currentMoveAction = null;
        currentMoveItems = [];
        updateBulkActions();
        updateInfoPanel();
    } catch (error) {
        console.error(`Unable to ${action} items`, error);
        const errorMsg = error.response?.data?.error || error.response?.data?.message || `Unable to ${action} item(s).`;
        alert(errorMsg);
    }
}

function updateInfoPanel() {
    const emptyState = document.getElementById("emptySelection");
    const detailsState = document.getElementById("fileDetails");

    if (!emptyState || !detailsState) {
        return;
    }

    if (!selectedItemId) {
        emptyState.classList.remove("d-none");
        detailsState.classList.add("d-none");
        return;
    }

    const file = getFile(selectedItemId);
    if (!file) {
        emptyState.classList.remove("d-none");
        detailsState.classList.add("d-none");
        return;
    }

    emptyState.classList.add("d-none");
    detailsState.classList.remove("d-none");

    document.getElementById("detailName").textContent = file.name;
    document.getElementById("detailType").textContent = file.type.toUpperCase();

    const previewIcon = document.getElementById("previewIcon");
    if (file.type === "image" && (file.thumbnail || file.url)) {
        previewIcon.innerHTML = `<img src="${file.thumbnail || file.url}" alt="${file.name}" class="img-fluid rounded" style="max-width: 100%; max-height: 200px; object-fit: contain;">`;
    } else {
        previewIcon.innerHTML = getIcon(file.type).replace("fs-1", "display-1");
    }

    const actionOpen = document.getElementById("actionOpen");
    const actionDownload = document.getElementById("actionDownload");

    if (file.type === "folder") {
        if (actionOpen) actionOpen.style.display = "none";
        if (actionDownload) actionDownload.style.display = "none";
        document.getElementById("infoType").textContent = "Folder";
    } else {
        if (actionOpen) actionOpen.style.display = "block";
        if (actionDownload) actionDownload.style.display = "block";
        document.getElementById("infoType").textContent = `${file.type.toUpperCase()} File`;
    }

    document.getElementById("infoSize").textContent = file.size;
    document.getElementById("infoDate").textContent = file.date;
    document.getElementById("infoLocation").textContent = file.location ?? "My Drive";
}

function formatBytes(bytes) {
    if (!bytes) {
        return "0 B";
    }

    const units = ["B", "KB", "MB", "GB", "TB"];
    const power = Math.min(units.length - 1, Math.floor(Math.log(bytes) / Math.log(1024)));
    const value = bytes / Math.pow(1024, power);
    return `${value.toFixed(1)} ${units[power]}`;
}

function toggleItemSelection(itemId) {
    if (selectedItems.has(itemId)) {
        selectedItems.delete(itemId);
    } else {
        selectedItems.add(itemId);
    }
    updateBulkActions();
    renderFiles();
}

function toggleSelectAll(e) {
    const isChecked = e.target.checked;
    if (isChecked) {
        mediaFiles.forEach(file => selectedItems.add(file.id));
    } else {
        selectedItems.clear();
    }
    updateBulkActions();
    renderFiles();
}

function updateBulkActions() {
    const bulkActions = document.querySelector('.bulk-actions');
    const selectedCount = document.getElementById('selectedCount');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');

    const count = selectedItems.size;

    if (count > 0) {
        if (bulkActions) bulkActions.style.display = 'inline-flex';
        if (selectedCount) {
            selectedCount.style.display = 'inline-block';
            selectedCount.textContent = `${count} selected`;
        }
    } else {
        if (bulkActions) bulkActions.style.display = 'none';
        if (selectedCount) selectedCount.style.display = 'none';
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.checked = count > 0 && count === mediaFiles.length;
        selectAllCheckbox.indeterminate = count > 0 && count < mediaFiles.length;
    }
}

async function bulkMove() {
    const items = Array.from(selectedItems);
    if (items.length === 0 || !axiosInstance) {
        return;
    }

    currentMoveAction = 'bulk-move';
    currentMoveItems = items;
    await showFolderSelector();
}

async function bulkCopy() {
    const items = Array.from(selectedItems);
    if (items.length === 0 || !axiosInstance) {
        return;
    }

    currentMoveAction = 'bulk-copy';
    currentMoveItems = items;
    await showFolderSelector();
}

async function bulkDownload() {
    const items = Array.from(selectedItems);
    if (items.length === 0) {
        return;
    }

    items.forEach(itemId => {
        const file = getFile(itemId);
        if (file && file.url && file.type !== 'folder') {
            const link = document.createElement("a");
            link.href = file.url;
            link.download = file.name;
            document.body.appendChild(link);
            link.click();
            link.remove();
        }
    });
}

async function bulkDelete() {
    const items = Array.from(selectedItems);
    if (items.length === 0 || !axiosInstance) {
        return;
    }

    if (!confirm(`Are you sure you want to delete ${items.length} item(s)?`)) {
        return;
    }

    try {
        const deletePromises = items.map(async (itemId) => {
            try {
                const deleteUrl = mediaRoutes.resourceTemplate.replace(":mediaId", itemId);
                await axiosInstance.delete(deleteUrl);
                return { success: true, id: itemId };
            } catch (error) {
                console.error(`Failed to delete item ${itemId}:`, error);
                return { success: false, id: itemId, error: error.response?.data?.error || error.message };
            }
        });

        const results = await Promise.all(deletePromises);
        const failed = results.filter(r => !r.success);

        if (failed.length > 0) {
            const errorMessages = failed.map(r => r.error).filter(Boolean);
            const errorMsg = errorMessages.length > 0
                ? errorMessages[0]
                : `Failed to delete ${failed.length} item(s).`;
            alert(errorMsg);
        }

        selectedItems.clear();
        selectedItemId = null;
        await loadMedia();
        updateBulkActions();
        updateInfoPanel();
    } catch (error) {
        console.error("Unable to delete items", error);
        const errorMsg = error.response?.data?.error || error.response?.data?.message || "Unable to delete some items.";
        alert(errorMsg);
    }
}