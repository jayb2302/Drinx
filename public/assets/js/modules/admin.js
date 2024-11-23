// /// Admin
// export function initializeAdmin() {
//     function fetchUsers() {
//         fetch('/searchAllUsers')
//             .then(response => response.json())
//             .then(data => {
//                 const users = data.users || [];
//                 renderUsers(users);
//             })
//             .catch(error => console.error('Error fetching users:', error));
//     }
    
//     function getStatusOptions(selectedStatus) {
//         const statuses = ['🟢', '🟡', '🔴'];
//         return statuses.map(status => {
//             const selected = status === selectedStatus ? 'selected' : '';
//             return `<option value="${status}" ${selected}>${status}</option>`;
//         }).join('');
//     }
//     function renderUsers(users) {
//         const userTableBody = document.getElementById('userTableBody');
//         userTableBody.innerHTML = '';
//         users.forEach(user => {
//             const row = document.createElement('tr');
//             row.innerHTML = `
//             <td>
//                 <a href="/uploads/user/${user.username}" class="view-profile">
//                     <img class="profile-pictue m" src="${user.profilePictureUrl}" alt="Profile picture of ${user.username}">
//                 </a>
//             </td>
//                 <td>${user.username}</td>
//                 <td>${user.email}</td>
//                 <td>${user.status}</td>
//                 <td>
//                     <form class="update-status-form">
//                         <input type="hidden" name="user_id" value="${user.id}">
//                         <select name="status">${getStatusOptions(user.status)}</select>
//                         <button type="submit">Update</button>
//                     </form>
//                 </td>
//             `;
//             userTableBody.appendChild(row);
//         });

//         attachFormListeners();
//     }

//     function attachFormListeners() {
//         document.querySelectorAll('.update-status-form').forEach(form => {
//             form.addEventListener('submit', function (e) {
//                 e.preventDefault();
    
//                 const formData = new FormData(this);
//                 const userId = form.dataset.userId; // Retrieve the user ID from the form's data attribute
    
//                 fetch('/admin/update-status', {
//                     method: 'POST',
//                     body: formData,
//                 })
//                     .then(response => response.json())
//                     .then(data => {
//                         if (data.success) {
//                             alert('User status updated successfully');
//                             fetchUsers(); // Refresh the table to reflect changes
//                         } else {
//                             alert('Failed to update user status');
//                         }
//                     })
//                     .catch(error => {
//                         console.error('Error updating user status:', error);
//                         alert('An error occurred while updating user status.');
//                     });
//             });
//         });
//     }

//     fetchUsers();
// }
