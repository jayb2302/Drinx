<div class="admin-container">
<h1 class="admin-control-title">Manage Users</h1>
<input type="text" id="userSearch" placeholder="Search users..." class="user-search-input">

<table class="manage-users">
    <thead>
        <tr>
            <th>Profiles</th>
            <th data-sort="username" class="sortable">Username <span class="sort-indicator"></span></th>
            <th data-sort="email" class="sortable">Email <span class="sort-indicator"></span></th>
            <th data-sort="status" class="sortable">Account Status <span class="sort-indicator"></span></th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="userTableBody">
        <?php foreach ($users as $user): ?>
            <tr class="users-rows">
                <td>
                    <a href="/user/<?= htmlspecialchars($user->getUsername()); ?>" class="view-profile">
                        <img class="profile-pic m" alt="Profile picture of <?= htmlspecialchars($user->getUsername()); ?>">
                    </a>
                </td>
                <td><?= htmlspecialchars($user->getUsername()); ?></td>
                <td><?= htmlspecialchars($user->getEmail()); ?></td>
                <td><?= htmlspecialchars($user->getAccountStatusName()); ?></td>
                <td>
                    <form class="update-status-form" data-user-id="<?= $user->getId(); ?>">
                        <input type="hidden" name="user_id" value="<?= $user->getId(); ?>">
                        <select name="status_id">
                            <option value="1" <?= $user->getAccountStatusId() == 1 ? '🟢' : '' ?>>🟢</option>
                            <option value="2" <?= $user->getAccountStatusId() == 2 ? '🟡' : '' ?>>🟡</option>
                            <option value="3" <?= $user->getAccountStatusId() == 3 ? '🔴' : '' ?>>🔴</option>
                        </select>
                        <button class="button" type="submit">Update Status</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>



