$(function () {
        // Cache selectors
        const $tagsManagement = $("#tagsManagement");
        const $tagDialog = $("#tagDialog");
        const $notificationDialog = $("#notificationDialog");
    
        // Toggle Tag Management visibility
        $("#toggleTagsManagementButton").on("click", function () {
            $tagsManagement.slideToggle();
        })

        $("#tagDialog").dialog({
            autoOpen: false,  // Ensure dialog is not open by default
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

    // Initialize the notification dialog
    $("#notificationDialog").dialog({
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

    /**
     * Opens the dialog for adding a new tag
     */
    function openDialogForAdd() {
        $("#tagId").val('');
        $("#tagName").val('');
        $("#tagCategory").val('');
        $("#tagDialog").dialog("open");
    }

    /**
     * Opens the dialog for editing a tag
     * @param {Object} $button - The jQuery object of the button clicked
     */
    function openDialogForEdit($button) {
        const tagId = $button.data("tag-id");
        const tagName = $button.data("tag-name");
        const tagCategoryId = $button.data("tag-category-id");
    
        // Set values in the dialog form
        $("#tagId").val(tagId);
        $("#tagName").val(tagName);
        $("#tagCategory").val(tagCategoryId); // Set the category based on the selected tag's category ID
    
        // Open the dialog
        $("#tagDialog").dialog("open");
    }

    /**
     * Handles saving a tag (add or update)
     */
    function handleSaveTag() {
        const tagId = $("#tagId").val();
        const tagName = $("#tagName").val().trim();
        const tagCategoryId = $("#tagCategory").val();

        if (!tagName || !tagCategoryId) {
            alert("All fields are required.");
            return;
        }

        const url = tagId ? '/admin/tag/save' : '/admin/tag/add';
        const data = tagId
            ? { tag_id: tagId, tag_name: tagName, tag_category_id: tagCategoryId }
            : { tag_name: tagName, tag_category_id: tagCategoryId };

        $.ajax({
            url: url,
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(data),
            success: function (response) {
                try {
                    const jsonResponse = JSON.parse(response);
                    if (jsonResponse.status === 'success') {
                        alert(jsonResponse.message);

                        // If editing an existing tag, update the tag in the UI
                        if (tagId) {
                            const tagItem = $(`.tag-item[data-tag-id="${tagId}"]`);
                            tagItem.find(".tag-name").text(tagName); // Update the tag name in the list
                            tagItem.find(".tag-category").text(tagCategoryId); // Update the category if needed
                        } else {
                            // Add the new tag to the list dynamically without refreshing the page
                            const newTagHtml = `
                                <li class="tag-item" data-tag-id="${jsonResponse.tag_id}">
                                    <span class="tag-name">${tagName}</span> - <span class="tag-category">${tagCategoryId}</span>
                                    <button class="editTagButton">Edit</button>
                                    <button class="deleteTagButton">Delete</button>
                                </li>
                            `;
                            $("#tagList").append(newTagHtml); // Add the new tag to the list
                        }

                        // Close the modal dialog
                        $("#tagDialog").dialog("close"); // Ensure this is the correct ID of your modal
                    } else {
                        alert(jsonResponse.message);
                    }
                } catch (error) {
                    alert('Failed to parse response');
                }
            },
            error: function () {
                alert("An error occurred while saving the tag.");
            }
        });
    }

    /**
     * Handles deleting a tag
     * @param {Object} $button - The jQuery object of the button clicked
     */
    function handleDeleteTag($button) {
        const tagId = $button.data("tag-id");
        const tagName = $button.data("tag-name");
    
        // Confirm deletion
        if (confirm(`Are you sure you want to delete the tag "${tagName}"?`)) {
            // Sending tag ID in the request body as JSON
            $.ajax({
                url: '/admin/tag/delete', // Make sure this URL is correct
                method: "POST",
                contentType: "application/json", // Correct content type for JSON data
                data: JSON.stringify({ tag_id: tagId }), // Ensure tag_id is sent in the request body
                success: function (response) {
                    try {
                        const jsonResponse = JSON.parse(response);
                        if (jsonResponse.status === 'success') {
                            alert(jsonResponse.message);
    
                            // Remove the tag from the UI without refreshing the page
                            $button.closest('.tag-item').remove(); // Adjust the selector based on your HTML structure
    
                            // Optionally, you can update the list of tags dynamically if needed
                            // fetchTags(); // You can implement this function to reload the tags list dynamically
                        } else {
                            alert(jsonResponse.message); // Display error message from response
                        }
                    } catch (error) {
                        console.error('Error parsing delete response:', error);
                        alert('Failed to parse response while deleting the tag.');
                    }
                },
                error: function () {
                    alert("An error occurred while deleting the tag.");
                }
            });
        }
    }
});