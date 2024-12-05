export function initializeUserManagement() {
    const userSearchInput = document.getElementById("userSearch");
    const userTableBody = document.getElementById("userTableBody");
    let sortOrder = 1; // Initialize sorting order
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');  // CSRF Token

    function sanitizeHTML(str) {
        const temp = document.createElement("div");
        temp.textContent = str;
        return temp.innerHTML;
    }

    function renderUsers(users) {
        userTableBody.innerHTML = ""; // Clear table
        users.forEach(user => {
            
            const profileImage = user.profile_picture && user.profile_picture !== "user-default.svg" 
                ? `/uploads/users/${encodeURIComponent(user.profile_picture)}`
                : "/uploads/users/user-default.svg";
                const statusCircle = {
                    1: '🟢', // Active
                    2: '🟡', // Suspended
                    3: '🔴'  // Banned
                };
        
            const row = document.createElement("tr");
            row.className = "users-rows";
            row.innerHTML = `
                <td>
                    <a href="/profile/${encodeURIComponent(user.username)}">
                        <img src="${profileImage}" width="40" height="40">
                    </a>
                </td>
                <td>${sanitizeHTML(user.username)}</td>
                <td>${sanitizeHTML(user.email)}</td>
                <td>
                <span class="status-circle">${statusCircle[user.account_status_id] || ''}</span>
                <span>${sanitizeHTML(user.account_status_name)}</span>
            </td>
                <td>
                    <form class="update-status-form" data-user-id="${user.user_id}">
                        <input type="hidden" name="user_id" value="${user.user_id}">
                        <select name="status_id">
                            <option value="1" ${user.account_status_id == 1 ? "selected" : ""}>🟢</option>
                            <option value="2" ${user.account_status_id == 2 ? "selected" : ""}>🟡</option>
                            <option value="3" ${user.account_status_id == 3 ? "selected" : ""}>🔴</option>
                        </select>
                        <button class="button" type="submit">Update Status</button>
                    </form>
                </td>
            `;
            userTableBody.appendChild(row);
        });
        attachStatusFormListeners();
    }

    function fetchAllUsers() {
        fetch(`/searchAllUsers`)
            .then(response => response.json())
            .then(data => {
                const users = data.users || [];
                renderUsers(users);
            })
            .catch(error => console.error("Error:", error));
    }

    userSearchInput.addEventListener("input", () => {
        const query = userSearchInput.value.trim();

        if (!query) {
            fetchAllUsers(); // Load all users if search is cleared
        } else {
            fetch(`/searchAllUsers?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    const users = data.users || [];
                    renderUsers(users);
                })
                .catch(error => console.error("Error:", error));
        }
    });

    function attachStatusFormListeners() {
        document.querySelectorAll("form.update-status-form").forEach(form => {
            form.addEventListener("submit", function(event) {
                event.preventDefault(); // Prevent default form submission

                const formData = new FormData(this);
                const row = this.closest("tr");

                formData.append('csrf_token', csrfToken);
                fetch("/admin/update-status", {
                    method: "POST",
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            alert(data.message);

                            // Update account status dynamically
                            const statusSelect = this.querySelector("select[name='status_id']");
                            const selectedStatusText = statusSelect.options[statusSelect.selectedIndex].textContent;
                            const statusDisplayCell = row.querySelector("td:nth-child(4)");
                            if (statusDisplayCell) {
                                statusDisplayCell.textContent = selectedStatusText;
                            }
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("An error occurred. Please try again.");
                    });
            });
        });
    }

    const tableHeaders = document.querySelectorAll(".manage-users th[data-sort]");
    tableHeaders.forEach(header => {
        header.addEventListener("click", () => {
            const sortBy = header.getAttribute("data-sort");
            sortTable(sortBy, header);
            sortOrder *= -1;
        });
    });

    function sortTable(sortBy, header) {
        const rows = Array.from(userTableBody.rows);

        document.querySelectorAll(".sortable").forEach(th => th.classList.remove("sort-asc", "sort-desc"));

        if (sortOrder === 1) {
            header.classList.add("sort-asc");
            header.classList.remove("sort-desc");
        } else {
            header.classList.add("sort-desc");
            header.classList.remove("sort-asc");
        }

        rows.sort((a, b) => {
            const cellA = a.querySelector(`td:nth-child(${getColumnIndex(sortBy)})`).textContent.trim().toLowerCase();
            const cellB = b.querySelector(`td:nth-child(${getColumnIndex(sortBy)})`).textContent.trim().toLowerCase();

            return cellA.localeCompare(cellB) * sortOrder;
        });

        rows.forEach(row => userTableBody.appendChild(row));
    }

    function getColumnIndex(sortBy) {
        switch (sortBy) {
            case "username":
                return 2;
            case "email":
                return 3;
            case "status":
                return 4;
            default:
                return 2;
        }
    }

    fetchAllUsers();
}