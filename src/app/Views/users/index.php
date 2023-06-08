<h1>Users List</h1>

<a href="javascript:history.back()">Go Back</a>

<hr>

<button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#createNewUserModal">
    Create New User
</button>

<div class="modal fade" id="createNewUserModal" tabindex="-1" aria-labelledby="createNewUserModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createNewUserModalLabel">Create New User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div x-data="{ formData: {} }">
                    <form @submit.prevent="submitForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                autocomplete="username" x-model="formData.username" required>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" name="email"
                                autocomplete="email" x-model="formData.email" required>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" name="password"
                                autocomplete="new-password" x-model="formData.password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>



        </div>
    </div>
</div>


<ol class="list-group list-group-numbered">
    <?php foreach ($users as $key => $user): ?>

        <li class="list-group-item d-flex justify-content-between align-items-start flex-wrap" x-data="{editMode: false}">
            <div class="ms-2 me-auto" x-show="!editMode" x-transition>
                <div class="fw-bold">
                    <?= $user->username ?>
                </div>
                <?= $user->email ?>
            </div>
            <div class="ms-2 me-auto" x-show="editMode" x-cloak x-transition.delay.100ms
                x-data="{updatedData: {}, update: (data) => { fetch('<?= url("/users/$user->id") ?>', {method: 'put', body:JSON.stringify(data)}).then(e => {location.reload()}) }}">
                <div>
                    <input type="text" class="form-control" placeholder="<?= $user->username ?>"
                        x-model="updatedData.username">
                </div>
                <input type="text" class="form-control form-control-sm" placeholder="<?= $user->email ?>"
                    x-model="updatedData.email">
                <div>
                    <button @click="update(updatedData)" class="btn btn-primary btn-sm my-1">Save</button>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="badge bg-info rounded-pill" @click="editMode= !editMode">Edit</button>
                <button class="badge bg-danger rounded-pill"
                    @click="fetch('<?= url("/users/$user->id") ?>', {method: 'DELETE'}).then(e => location.reload())">Delete</button>
            </div>
        </li>

    <?php endforeach; ?>
</ol>

<script>
    function submitForm() {

        fetch('<?= url("/users") ?>', {
            method: 'POST',
            body: JSON.stringify(this.formData),
        }).then(e => location.reload())

    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('formData', {
            username: '',
            email: '',
            password: '',
            submitForm,
        });
    });
</script>