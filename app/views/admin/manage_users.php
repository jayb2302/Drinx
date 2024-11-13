<h2>Manage Users</h2>
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
                    <a href="/profile/<?= htmlspecialchars($user->getUsername()); ?>" class="view-profile">
                        <img class="" alt="">
                    </a>
                </td>
                <td><?= htmlspecialchars($user->getUsername()); ?></td>
                <td><?= htmlspecialchars($user->getEmail()); ?></td>
                <td><?= htmlspecialchars($user->getAccountStatusName()); ?></td>
                <td>
                    <form class="update-status-form" data-user-id="<?= $user->getId(); ?>">
                        <input type="hidden" name="user_id" value="<?= $user->getId(); ?>">
                        <select name="status_id">
                            <option value="1" <?= $user->getAccountStatusId() == 1 ? 'selected' : '' ?>>Active</option>
                            <option value="2" <?= $user->getAccountStatusId() == 2 ? 'selected' : '' ?>>Suspended</option>
                            <option value="3" <?= $user->getAccountStatusId() == 3 ? 'selected' : '' ?>>Banned</option>
                        </select>
                        <button class="button" type="submit">Update Status</button>
                    </form>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>




