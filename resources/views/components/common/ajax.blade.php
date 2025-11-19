<script>
    document.querySelectorAll(".ajax-form").forEach((form) => {
        form.addEventListener("submit", function(e) {
            e.preventDefault();

            const action = form.getAttribute("action");
            const method = form.getAttribute("method") || "POST";
            const formData = new FormData(form);

            const submitButton = form.querySelector('button[type="submit"]');
            const spinner = submitButton.querySelector(".btn-progress");
            const btnLabel = submitButton.querySelector(".btn-label");

            // Show spinner
            if (spinner) {
                spinner.classList.remove("d-none");
                btnLabel.classList.add("d-none");
                submitButton.disabled = true;
            }

            // Clear previous error messages
            form.querySelectorAll(".error").forEach((el) => (el.textContent = ""));
            form.querySelectorAll(".input-error").forEach((el) =>
                el.classList.remove("is-invalid", "input-error")
            );
            form.querySelectorAll(".input-group-error").forEach((el) =>
                el.classList.remove("input-group-error")
            );

            // Send AJAX request
            fetch(action, {
                    method: method,
                    body: formData,
                    headers: {
                        Accept: "application/json",
                    },
                })
                .then((response) => {
                    if (response.status === 422) {
                        // Handle Laravel validation errors
                        return response.json().then((data) => {
                            const errors = data.errors;

                            // Display validation errors in existing elements
                            for (const key in errors) {
                                const field = form.querySelector(
                                    `[name="${key}"]`);
                                const errorElement = form.querySelector(
                                    `.error[data-error="${key}"]`);

                                if (field) {
                                    // Check if field is inside input-group
                                    const inputGroup = field.closest('.input-group');
                                    if (inputGroup) {
                                        // If inside input-group, only add error class to input-group
                                        inputGroup.classList.add("input-group-error");
                                    } else {
                                        // If not inside input-group, add error classes to field
                                        field.classList.add("is-invalid", "input-error");
                                    }
                                }

                                if (errorElement) {
                                    errorElement.textContent = errors[key][0];
                                }
                            }
                        });
                    }

                    if (!response.ok) {
                        console.error("Error:", response);
                        alert("Error! Something went wrong.");
                        return;
                    }

                    // Ensure the response is valid JSON
                    return response.json();
                })
                .then((data) => {
                    // Hide spinner
                    if (spinner) {
                        spinner.classList.add("d-none");
                        btnLabel.classList.remove("d-none");
                        submitButton.disabled = false;
                    }

                    // Redirect if specified
                    if (data?.success) {
                        if (data?.data && data?.data?.redirect) {
                            alert("Success! " + data.message);
                            location.href = data?.data?.redirect;
                        } else {
                            alert("Success! " + data?.message);
                        }
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);

                    // Hide spinner
                    if (spinner) {
                        spinner.classList.add("d-none");
                        btnLabel.classList.remove("d-none");
                        submitButton.disabled = false;
                    }

                    // Show a general error message
                    // alert("Something went wrong. Please try again.");
                });
        });
    });

    const deleteButton = document.querySelectorAll(".delete");
    deleteButton.forEach((button) => {
        button.addEventListener("click", function(e) {
            e.preventDefault();
            const url = button.getAttribute("data-delete-url");

            if (confirm("Are you sure? You won't be able to revert this!")) {
                fetch(url, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": '{{ csrf_token() }}',
                        },
                    })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error("Network response was not ok");
                        }
                        return response.json();
                    })
                    .then((data) => {
                        console.log(data);
                        if (data.status == true) {
                            location.reload();
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        alert("Something went wrong. Please try again.");
                    });
            }
        });
    });
</script>
