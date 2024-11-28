export function initializeIngredients() {
    // Toggle Ingredient Management visibility
    $("#toggleIngredientManagementButton").click(function () {
        $("#ingredientManagement").slideToggle();
    });

    // Initialize Assign Tag Dialog
    $("#assignTagDialog").dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            "Assign Tag": handleAssignTag,
            Cancel: function () {
                $(this).dialog("close");
            }
        }
    });

    // Initialize Add Ingredient Modal
    console.log("Initializing Add Ingredient Modal");
    $("#addIngredientDialog").dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            "Add Ingredient": handleAddIngredient, // Ensure this function is defined
            Cancel: function () {
                $(this).dialog("close");
                $("#ingredientNameInput").val(""); // Clear input field
            }
        }
    });

    // Open Add Ingredient Modal
    $("#openAddIngredientButton").click(function () {
        $("#addIngredientDialog").dialog("open");
    });

    // Handle adding a new ingredient
    function handleAddIngredient(event) {
        event.preventDefault();
        const ingredientName = $("#ingredientNameInput").val().trim();

        if (!ingredientName) {
            alert("Please enter an ingredient name.");
            return;
        }

        $.ajax({
            url: "/admin/ingredients/create",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify({ ingredient_name: ingredientName }),
            success: function (response) {
                console.log("Server Response:", response);  // Log the raw response
                
                // Check if response is a string, and parse it into an object if necessary
                if (typeof response === "string") {
                    try {
                        response = JSON.parse(response);  // Parse the response string into an object
                    } catch (error) {
                        console.error("Error parsing response:", error);
                        alert("Error parsing the response from the server.");
                        return;
                    }
                }

                // Now check the response status and act accordingly
                if (response.status === "success") {
                    alert(response.message);  // Show success message
                    $("#addIngredientDialog").dialog("close");  // Close modal if needed
                    fetchUncategorizedIngredients();  // Refresh the ingredient list
                } else {
                    alert(response.message || "Error adding ingredient.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
                console.log("Response Text:", xhr.responseText); // Log the raw response text from the server
                alert("Error adding ingredient: " + xhr.responseText); // Show the error message in the alert
            }
        });
    }

    // Open Assign Tag Dialog
    $(document).on("click", ".assignTagButton", function () {
        console.log("Assign Tag button clicked"); // Debug log
        const ingredientId = $(this).closest("li").data("ingredient-id");
        const ingredientName = $(this).closest("li").find(".ingredient-name").text().trim();

        $("#ingredientId").val(ingredientId); // Set the hidden ingredient ID
        $("#ingredientName").text(ingredientName); // Set the ingredient name in the modal

        console.log("Ingredient ID:", ingredientId, "Ingredient Name:", ingredientName); // Debug
        $("#editIngredientContainer").show();

        // Fetch tags and open the dialog
        fetchTagsWithCategories(() => {
            console.log("Tags fetched successfully.");
            $("#assignTagDialog").dialog("open"); // Open modal
        });
    });

    // Handle editing ingredient name
    $(document).on("click", ".editIngredientButton", function () {
        const ingredientId = $(this).closest("li").data("ingredient-id");
        const ingredientName = $(this).closest("li").find(".ingredient-name").text();
    
        // Open a prompt or modal to edit the ingredient name
        const newIngredientName = prompt("Edit Ingredient Name", ingredientName);
    
        if (newIngredientName && newIngredientName !== ingredientName) {
            $.ajax({
                url: "/admin/ingredients/edit",
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    ingredient_id: ingredientId,
                    ingredient_name: newIngredientName,
                }),
                success: function (response) {
                    console.log("Response from server:", response);
    
                    if (typeof response === "string") {
                        try {
                            response = JSON.parse(response);
                        } catch (e) {
                            console.error("Failed to parse response:", e);
                            alert("Unexpected response format.");
                            return;
                        }
                    }
    
                    if (response.status === "success") {
                        alert(response.message || "Ingredient updated successfully.");
    
                        // Update the UI directly for uncategorized ingredients
                        const $ingredientElement = $(
                            `li[data-ingredient-id='${ingredientId}'] .ingredient-name`
                        );
                        $ingredientElement.text(newIngredientName);
    
                        // Alternatively, refresh the entire uncategorized list
                        fetchUncategorizedIngredients();
                    } else {
                        alert(response.message || "Failed to update ingredient.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", error);
                    console.log("Response Text:", xhr.responseText);
                    alert("An error occurred while updating the ingredient name.");
                },
            });
        }
    });
    $(document).on("click", ".deleteIngredientButton", function () {
        const ingredientId = $(this).data("ingredient-id");

        // Confirm deletion
        if (confirm("Are you sure you want to delete this ingredient?")) {
            $.ajax({
                url: "/admin/ingredients/delete",  // Your backend endpoint to delete the ingredient
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify({ ingredient_id: ingredientId }),
                success: function (response) {
                    console.log("Delete response:", response);  // Log the response

                    // Ensure response is parsed correctly (if it's a string, parse it)
                    if (typeof response === "string") {
                        try {
                            response = JSON.parse(response);
                        } catch (e) {
                            console.error("Error parsing server response:", e);
                            alert("Failed to parse response.");
                            return;
                        }
                    }
                    fetchUncategorizedIngredients(); 
                },
                error: function (xhr, status, error) {
                    console.error("Delete Ingredient Error:", error);
                    alert("An error occurred while deleting the ingredient.");
                }
            });
        }
    });

    function fetchUncategorizedIngredients() {
        $.ajax({
            url: '/admin/ingredients/uncategorized',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                console.log("Fetched uncategorized ingredients:", response);
    
                if (response.status === 'success') {
                    renderUncategorizedIngredients(response.ingredients);
                } else {
                    console.error('Error fetching ingredients:', response.message);
                    alert(response.message || "Failed to fetch uncategorized ingredients.");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('AJAX error:', textStatus, errorThrown);
                console.log('Raw response:', jqXHR.responseText);
            }
        });
    }
    
    function renderUncategorizedIngredients(ingredients) {
        const $list = $("#uncategorizedIngredients");
        $list.empty();
    
        if (ingredients.length === 0) {
            $list.append("<li>No uncategorized ingredients found.</li>");
            return;
        }
    
        ingredients.forEach(ingredient => {
            const $item = $("<li>").data("ingredient-id", ingredient.ingredient_id);
    
            const $ingredientName = $("<span>")
                .addClass("ingredient-name")
                .text(ingredient.name);
    
            const $buttonsDiv = $("<div>").addClass("ingredient-buttons");
    
            const $assignTagButton = $("<button>")
                .text("🏷️")
                .addClass("assignTagButton");
    
            const $editButton = $("<button>")
                .text("🖊️")
                .addClass("editIngredientButton")
                .data("ingredient-id", ingredient.ingredient_id)
                .data("ingredient-name", ingredient.ingredient_name);
    
            const $deleteButton = $("<button>")
                .text("🗑️")
                .addClass("deleteIngredientButton")
                .data("ingredient-id", ingredient.ingredient_id);
    
                $buttonsDiv.append($assignTagButton, $editButton, $deleteButton);
            $item.append($ingredientName, $buttonsDiv);
            $list.append($item);
        });
    }
    fetchUncategorizedIngredients();

    // Fetch tags with categories
    function fetchTagsWithCategories(callback) {
        $.ajax({
            url: "/admin/tags", // Correct route for fetching tags
            method: "GET",
            dataType: "json", // Expecting JSON response
            success: function (response) {
                // Check if the response has a success status and tags
                if (response.status === "success" && response.groupedTags) {
                    console.log("Tags fetched:", response.groupedTags);

                    const $dropdown = $("#tag");
                    $dropdown.empty(); // Clear existing options

                    // Group tags by category
                    const groupedTags = response.groupedTags; // Already grouped by category

                    // Populate the dropdown with optgroups
                    for (const category in groupedTags) {
                        const $optgroup = $("<optgroup>").attr("label", category); // Label as the category name

                        // Iterate over each tag in the category and add it as an option
                        groupedTags[category].forEach(tag => {
                            $optgroup.append(
                                $("<option>")
                                    .val(tag.tag_id) // Set tag ID as the value
                                    .text(tag.name) // Set tag name as the text
                            );
                        });

                        $dropdown.append($optgroup); // Append the optgroup to the dropdown
                    }

                    if (callback) callback(); // Trigger callback if provided
                } else {
                    alert(response.message || "Failed to fetch tags.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching tags:", error);
                console.log("Raw response:", xhr.responseText); // Debug raw response
            }
        });
    }

    function handleAssignTag() {
        const ingredientId = $("#ingredientId").val();
        const tagId = $("#tag").val();

        console.log("Assigning Tag - Ingredient ID:", ingredientId, "Tag ID:", tagId);

        if (!ingredientId || !tagId) {
            alert("Please select a valid tag.");
            return;
        }

        $.ajax({
            url: "/admin/ingredients/assign-tag",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify({ ingredient_id: ingredientId, tag_id: tagId }),
            success: function (response) {
                console.log("Assign Tag Response:", response);

                // Ensure response is an object
                if (typeof response === "string") {
                    try {
                        response = JSON.parse(response); // Parse if it's a string
                    } catch (e) {
                        console.error("Failed to parse response:", e);
                        alert("An unexpected error occurred while processing the response.");
                        return;
                    }
                }

                if (response.status === "success") {
                    alert(response.message || "Tag assigned successfully.");
                    fetchUncategorizedIngredients(); // Refresh ingredient list
                    $("#assignTagDialog").dialog("close");
                } else {
                    alert(response.message || "Failed to assign tag.");
                    console.error("Server responded with error:", response);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Assign Tag Error:", error);
                console.log("Response Text:", xhr.responseText);

                let errorMessage = "An error occurred while assigning the tag.";
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.message) {
                        errorMessage = errorResponse.message;
                    }
                } catch (e) {
                    console.error("Error parsing server response:", e);
                }

                alert(errorMessage);
            }
        });
    }
}