$(function () {
    // Toggle Ingredient Management visibility
    $("#toggleIngredientManagementButton").click(function () {
        $("#ingredientManagement").slideToggle();
    });

    // Initialize Assign Tag Dialog
    $("#assignTagDialog").dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            '🏷️': handleAssignTag,
            '❌': function () {
                $(this).dialog("close");
            }
        }
    });

    // Initialize Add Ingredient Modal
    $("#addIngredientModal").dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            Add: function () {
                handleAddIngredient();
            },
            Cancel: function () {
                $(this).dialog("close");
                $("#ingredientName").val("");
            }
        }
    });

    // Open Add Ingredient Modal
    $("#adminAddIngredientButton").click(function () {
        $("#addIngredientModal").dialog("open");
    });

    // // Handle adding a new ingredient
    // function handleAddIngredient() {
    //     const ingredientName = $("#ingredientName").val().trim();
    
    //     if (!ingredientName) {
    //         alert("Please enter an ingredient name.");
    //         return;
    //     }
    
    //     $.ajax({
    //         url: "/admin/ingredients/create",
    //         method: "POST",
    //         contentType: "application/json",
    //         data: JSON.stringify({ ingredient_name: ingredientName }),
    //         success: function(response) {
    //             console.log("Server Response:", response); // Log the raw response
    //             try {
    //                 const parsedResponse = JSON.parse(response);  // Ensure it's a valid JSON object
            
    //                 // Check if the response is successful
    //                 if (parsedResponse.status === "success") {
    //                     alert(parsedResponse.message);  // Show success message
    //                     // Proceed to update the DOM or refresh the list
    //                 } else {
    //                     alert(parsedResponse.message || "Error adding ingredient.");
    //                 }
    //             } catch (error) {
    //                 console.error("Error parsing response:", error);
    //                 alert("There was an issue with the server response.");
    //             }
    //         },
    //         error: function (xhr, status, error) {
    //             console.error("Error:", error);
    //             console.log("Response Text:", xhr.responseText);  // Log the raw response text from the server
    //             alert("Error adding ingredient: " + xhr.responseText);  // Show the error message in the alert
    //         }
    //     });
    // }
    // Open Assign Tag Dialog
    $(document).on("click", ".assignTagButton", function () {
        const ingredientId = $(this).closest("li").data("ingredient-id");
        const ingredientName = $(this).closest("li").find(".ingredient-name").text().trim();

        // const ingredientName = $(this).closest("li").contents().filter(function () {
        //     return this.nodeType === 3; // Text nodes only
        // }).text().trim();
        $("#ingredientId").val(ingredientId);
        $("#ingredientNameDisplay").text(ingredientName);
        $("#ingredientNameEdit").val(ingredientName);

        console.log("Ingredient Name Before Modal:", ingredientName);

        $("#editIngredientContainer").show();

        fetchTagsWithCategories(() => {
            $("#assignTagDialog").dialog("open");
        });
    });

    // Handle editing ingredient name
    $(document).on("click", ".editIngredientButton", function () {
        const ingredientId = $(this).data("ingredient-id");
        const ingredientName = $(this).data("ingredient-name");

        console.log("Ingredient ID:", ingredientId); // Check if the ingredient ID is valid
        console.log("Ingredient Name:", ingredientName); // Check if the ingredient name is valid

        // Open a prompt or modal to edit the ingredient name
        const newIngredientName = prompt("Edit Ingredient Name", ingredientName);

        if (newIngredientName && newIngredientName !== ingredientName) {
            $.ajax({
                url: "/admin/ingredients/edit",  // Your backend endpoint to handle name update
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify({ ingredient_id: ingredientId, ingredient_name: newIngredientName }),
                success: function (response) {
                    console.log("Response from server:", response); // Log the response to see what the server is returning
                    fetchUncategorizedIngredients();  // Refresh the ingredient list (optional)
                },
                error: function (xhr, status, error) {
                    console.error("Edit Ingredient Error:", error);
                    alert("An error occurred while updating the ingredient name.");
                }
            });
        }
    });

    // Handle deleting ingredient
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

     // Fetch uncategorized ingredients
     function fetchUncategorizedIngredients() {
        $.ajax({
            url: "/admin/ingredients/uncategorized",
            method: "GET",
            success: function (response) {
                if (response.status === "success") {
                    renderUncategorizedIngredients(response.ingredients);  // Make sure this renders the updated list
                } else {
                    alert("No uncategorized ingredients found.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Fetch Ingredients Error:", error, xhr.responseText);
                alert("Failed to fetch uncategorized ingredients.");
            }
        });
    }

    // Render uncategorized ingredients
    function renderUncategorizedIngredients(ingredients) {
        const $list = $("#uncategorizedIngredients");
        $list.empty();

        if (ingredients.length === 0) {
            $list.append("<li>No uncategorized ingredients found.</li>");
            return;
        }

        ingredients.forEach(ingredient => {
            const $item = $("<li>")
                .data("ingredient-id", ingredient.ingredient_id);

            const $ingredientName = $("<span>")
                .addClass("ingredient-name")
                .text(ingredient.name);

            // Create a div to hold the buttons
            const $buttonsDiv = $("<div>").addClass("ingredient-buttons");

            // Assign Tag button
            const $assignTagButton = $("<button>")
                .text("🏷️")
                .addClass("assignTagButton");

            // Edit Ingredient button
            const $editButton = $("<button>")
                .text("🖊️")
                .addClass("editIngredientButton")
                .data("ingredient-id", ingredient.ingredient_id)
                .data("ingredient-name", ingredient.name);

            // Delete Ingredient button
            const $deleteButton = $("<button>")
                .text("🗑️")
                .addClass("deleteIngredientButton")
                .data("ingredient-id", ingredient.ingredient_id);

            // Append the ingredient name and buttons
            $item.append($ingredientName, $buttonsDiv);
            $buttonsDiv.append($assignTagButton, $editButton, $deleteButton);

            // Append the list item to the list
            $list.append($item);
        });
    }
    function handleAddIngredient() {
        const ingredientName = $("#ingredientName").val().trim();
        
        if (!ingredientName) {
            alert("Please enter an ingredient name.");
            return;
        }
    
        // AJAX call to add a new ingredient
        $.ajax({
            url: "/admin/ingredients/create",  
            method: "POST",                    
            contentType: "application/json", 
            data: JSON.stringify({ ingredient_name: ingredientName }),  // Data to be sent
            success: function (response) {
                try {
                    // Parse the response from the server
                    const parsedResponse = JSON.parse(response);
    
                    if (parsedResponse.status === "success") {
                        alert(parsedResponse.message);  // Show success message
    
                        // Dynamically add the new ingredient to the list
                        const newIngredient = $("<li>")
                            .data("ingredient-id", parsedResponse.ingredient_id)  // Use the new ingredient's ID
                            .append(`<span class="ingredient-name">${ingredientName}</span>`)
                            .append(`
                                <div class="ingredient-buttons">
                                    <button class="assignTagButton">🏷️</button>
                                    <button class="editIngredientButton">🖊️</button>
                                    <button class="deleteIngredientButton">🗑️</button>
                                </div>
                            `);
    
                        // Prepend the new ingredient to the list or append if preferred
                        $("#uncategorizedIngredients").prepend(newIngredient);
    
                        // Clear the input after adding the ingredient
                        $("#ingredientName").val("");
                        $("#addIngredientModal").dialog("close");  // Close the modal after adding
                    } else {
                        alert(parsedResponse.message || "Error adding ingredient.");
                    }
                } catch (error) {
                    console.error("Error parsing response:", error);
                    alert("There was an issue with the server response.");
                }
            },
            error: function (xhr, status, error) {
                // Log the error and show the alert
                console.error("Error:", error);
                console.log("Response Text:", xhr.responseText);
                alert("Error adding ingredient: " + xhr.responseText);
            }
        });
    }
    // Fetch tags grouped by categories
    function fetchTagsWithCategories(callback) {
        $.ajax({
            url: "/admin/tags",
            method: "GET",
            success: function (response) {
                if (response.status === "success") {
                    const $dropdown = $("#tag");
                    $dropdown.empty();

                    // Group tags by category
                    const groupedTags = {};
                    response.tags.forEach(tag => {
                        const category = tag.category_name || "Uncategorized";
                        if (!groupedTags[category]) {
                            groupedTags[category] = [];
                        }
                        groupedTags[category].push(tag);
                    });

                    // Populate the dropdown
                    for (const [category, tags] of Object.entries(groupedTags)) {
                        const $optgroup = $("<optgroup>").attr("label", category);
                        tags.forEach(tag => {
                            $optgroup.append(
                                $("<option>").val(tag.tag_id).text(tag.name)
                            );
                        });
                        $dropdown.append($optgroup);
                    }

                    if (callback) callback();
                } else {
                    alert("No tags found.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Fetch Tags Error:", error, xhr.responseText);
                alert("Failed to fetch tags.");
            }
        });
    }

    // Handle tag assignment
    function handleAssignTag() {
        const ingredientId = $("#ingredientId").val();
        const tagId = $("#tag").val();
        
        console.log("Ingredient ID:", ingredientId);  // Debugging line
        console.log("Tag ID:", tagId);  // Debugging line
        
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
                console.log("Server Response:", response);  // Log the response to verify it
    
                try {
                    if (typeof response === "string") {
                        response = JSON.parse(response);  // Ensure it is parsed properly
                    }
                    
                    if (response.status === "success") {
                        alert(response.message);
                        updateIngredientTagInList(ingredientId, tagId);  // Update UI
                        fetchUncategorizedIngredients();  // Optionally refresh list
                        $("#assignTagDialog").dialog("close");
                    } else {
                        alert(response.message || "Error assigning tag.");
                    }
                } catch (e) {
                    console.error("Error processing server response:", e);
                    alert("Failed to process the server response.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Assign Tag Error:", error);
                alert("An error occurred while assigning the tag.");
            }
        });
    }
    // function handleRemoveTag() {
    //     const ingredientId = $("#ingredientId").val();
    //     const tagId = $("#tag").val();  // Get the selected tag ID

    //     if (!ingredientId || !tagId) {
    //         alert("Please select a valid tag to remove.");
    //         return;
    //     }

    //     $.ajax({
    //         url: "/admin/ingredients/remove-tag",  // Your remove tag endpoint
    //         method: "POST",
    //         contentType: "application/json",
    //         data: JSON.stringify({ ingredient_id: ingredientId, tag_id: tagId }),
    //         success: function (response) {
    //             if (response.status === "success") {
    //                 alert(response.message);
    //                 fetchUncategorizedIngredients();  // Refresh the uncategorized ingredients list
    //                 $("#assignTagDialog").dialog("close");
    //             } else {
    //                 alert(response.message || "Error removing tag.");
    //             }
    //         },
    //         error: function (xhr, status, error) {
    //             console.error("Remove Tag Error:", error);
    //             alert("An error occurred while removing the tag.");
    //         }
    //     });
    // }
    // function handleEditTag() {
    //     const ingredientId = $("#ingredientId").val();
    //     const newTagId = $("#tag").val();  // Get the new tag ID selected by admin

    //     if (!ingredientId || !newTagId) {
    //         alert("Please select a valid tag to edit.");
    //         return;
    //     }

    //     $.ajax({
    //         url: "/admin/ingredients/edit-tag",  // Your edit tag endpoint
    //         method: "POST",
    //         contentType: "application/json",
    //         data: JSON.stringify({ ingredient_id: ingredientId, tag_id: newTagId }),
    //         success: function (response) {
    //             if (response.status === "success") {
    //                 alert(response.message);
    //                 fetchUncategorizedIngredients();  // Refresh the uncategorized ingredients list
    //                 $("#assignTagDialog").dialog("close");
    //             } else {
    //                 alert(response.message || "Error editing tag.");
    //             }
    //         },
    //         error: function (xhr, status, error) {
    //             console.error("Edit Tag Error:", error);
    //             alert("An error occurred while editing the tag.");
    //         }
    //     });
    // }
    function updateIngredientTagInList(ingredientId, tagId) {
        console.log('Updating ingredient tag:', ingredientId, tagId); // Debug log
        const ingredientItem = document.querySelector(`#ingredientList li[data-ingredient-id='${ingredientId}']`);
    
        if (ingredientItem) {
            const tagElement = ingredientItem.querySelector("span:nth-child(2)");
            if (tagElement) {
                const tagName = $("#tag option:selected").text();
                const categoryName = $("#tag option:selected").closest("optgroup").attr("label");
                tagElement.textContent = `${tagName} (${categoryName || 'No category'})`;
            }
        } else {
            console.log('Ingredient not found in the list:', ingredientId); // Debug log
        }
    }
    // Initial fetch for uncategorized ingredients
    fetchUncategorizedIngredients();
});