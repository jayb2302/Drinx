export function initializeTags() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const $tagsManagement = $("#tagsManagement");
    const $tagDialog = $("#tagDialog");
    const $notificationDialog = $("#notificationDialog");


    // Toggle Tag Management 
    $("#toggleTagsManagementButton").on("click", function () {
        $tagsManagement.slideToggle();
    });

    // Initialize Tag Dialog
    $tagDialog.dialog({
        autoOpen: false, 
        modal: true,
        buttons: {
            Save: function () {
                handleSaveTag();
            },
            Cancel: function () {
                $(this).dialog("close");
            }
        }
    });

    // Initialize Notification Dialog
    $notificationDialog.dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            Close: function () {
                $(this).dialog("close");
            }
        }
    });

    // Handle Add Tag button click
    $("#addTagButton").click(function () {
        openDialogForAdd();
    });

    // Handle Edit Tag button click
    $(document).on("click", ".editTagButton", function () {
        openDialogForEdit($(this));
    });

    // Handle Delete Tag button click
    $(document).on("click", ".deleteTagButton", function () {
        handleDeleteTag($(this));
    });

    // Function to open the dialog for adding a tag
    function openDialogForAdd() {
        $("#tagId").val('');
        $("#tagName").val('');
        $("#tagCategory").val('');
        $tagDialog.dialog("open");
    }

    // Function to open the dialog for editing a tag
    function openDialogForEdit($button) {
        const tagId = $button.data("tag-id");
        const tagName = $button.data("tag-name");
        const tagCategoryId = $button.data("tag-category-id");

        // Set values in the dialog form
        $("#tagId").val(tagId);
        $("#tagName").val(tagName);
        $("#tagCategory").val(tagCategoryId);

        // Open the dialog
        $tagDialog.dialog("open");
    }

    // Function to handle saving a tag (add or update)
    function handleSaveTag() {
        const tagId = $("#tagId").val();
        const tagName = $("#tagName").val().trim();
        const tagCategoryId = $("#tagCategory").val();
        const tagCategoryName = $("#tagCategory option:selected").text().trim(); 
    
        if (!tagName || !tagCategoryId) {
            alert("All fields are required.");
            return;
        }

        const url = tagId ? '/admin/tag/save' : '/admin/tag/add';
        const data = tagId
        ? { tag_id: tagId, tag_name: tagName, tag_category_id: tagCategoryId, csrf_token: csrfToken }
        : { tag_name: tagName, tag_category_id: tagCategoryId, csrf_token: csrfToken };
    
        $.ajax({
            url: url,
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            contentType: "application/json",
            data: JSON.stringify(data),
            success: function (response) {
                try {
                    const jsonResponse = typeof response === "string" ? JSON.parse(response) : response;
    
                    if (jsonResponse.status === 'success') {
                        alert(jsonResponse.message);
    
                        const existingTag = $(`.tag-item .editTagButton[data-tag-id="${tagId}"]`).closest(".tag-item");
    
                        if (tagId) {
                            // Tag is being edited
                            existingTag.find(".tag-name").text(tagName);
                            existingTag.find(".editTagButton").data("tag-name", tagName).data("tag-category-id", tagCategoryId);
    
                            // Check if the category has changed
                            const currentCategoryBlock = existingTag.closest(".category-block");
                            const currentCategoryName = currentCategoryBlock.find("h2").text().trim();
    
                            if (currentCategoryName !== tagCategoryName) {
                                // Move the tag to the new category
                                existingTag.remove();
    
                                const newCategoryBlock = $(`.category-block`).filter(function () {
                                    return $(this).find("h2").text().trim() === tagCategoryName;
                                });
    
                                if (newCategoryBlock.length > 0) {
                                    newCategoryBlock.find("ul").append(existingTag);
                                } else {
                                    alert("Selected category does not exist. Please reload the page.");
                                }
                            }
                        } else {
                            // Add new tag dynamically
                            const newTagHtml = `
                                <li class="tag-item">
                                    <span class="tag-name">${tagName}</span>
                                    <div class="button-group">
                                        <button class="button-secondary editTagButton" 
                                            data-tag-id="${jsonResponse.tag_id}" 
                                            data-tag-name="${tagName}" 
                                            data-tag-category-id="${tagCategoryId}">
                                            🖊️
                                        </button>
                                        <button class="button-error deleteTagButton" 
                                            data-tag-id="${jsonResponse.tag_id}" 
                                            data-tag-name="${tagName}">
                                            🗑️
                                        </button>
                                    </div>
                                </li>
                            `;
    
                            const newCategoryBlock = $(`.category-block`).filter(function () {
                                return $(this).find("h2").text().trim() === tagCategoryName;
                            });
    
                            if (newCategoryBlock.length > 0) {
                                newCategoryBlock.find("ul").append(newTagHtml);
                            } else {
                                alert("Selected category does not exist. Please reload the page.");
                            }
                        }
    
                        // Close the dialog
                        $("#tagDialog").dialog("close");
                    } else {
                        alert(jsonResponse.message); // Show error message
                    }
                } catch (error) {
                    console.error('Error parsing response:', error);
                    alert('Failed to parse the response while saving the tag.');
                }
            },
            error: function () {
                alert("An error occurred while saving the tag.");
            }
        });
    }
    // Function to handle deleting a tag
    function handleDeleteTag($button) {
        const tagId = $button.data("tag-id");
        const tagName = $button.data("tag-name");
    
        // Get the CSRF token from the meta tag
        const csrfToken = $('meta[name="csrf-token"]').attr('content'); 
        if (confirm(`Are you sure you want to delete the tag "${tagName}"?`)) {
            const data = {
                tag_id: tagId,
                csrf_token: csrfToken
            };
    
            // Perform the AJAX request
            $.ajax({
                url: '/admin/tag/delete',
                method: "POST",
                data: JSON.stringify(data), // Convert the data to a JSON string
                success: function (response) {
                    try {
                        const jsonResponse = typeof response === "string" ? JSON.parse(response) : response;
    
                        if (jsonResponse.status === 'success') {
                            alert(jsonResponse.message);
                            $button.closest('.tag-item').remove();  // Remove the tag item from the DOM
                        } else {
                            alert(jsonResponse.message);
                        }
                    } catch (error) {
                        console.error("Error parsing delete response:", error);
                        alert("Failed to parse the response while deleting the tag.");
                    }
                },
                error: function () {
                    alert("An error occurred while deleting the tag.");
                }
            });
        }
    }
}