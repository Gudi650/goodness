<script>
    function showLoader(event, loaderId) {
        if (event) {
            event.preventDefault();
        }

        const loader = document.getElementById(loaderId);
        const form = event?.currentTarget || event?.target;

        if (loader) {
            loader.classList.remove('hidden');
            loader.classList.add('flex');
        }

        setTimeout(() => {
            if (form) {
                form.submit();
            }
        }, 75);

        return true;
    }

    const tabUsersBtn = document.getElementById('tabUsersBtn');
    const tabRolesBtn = document.getElementById('tabRolesBtn');
    const usersPane = document.getElementById('usersPane');
    const rolesPane = document.getElementById('rolesPane');

    function showUsersPane() {
        if (!usersPane || !rolesPane || !tabUsersBtn || !tabRolesBtn) {
            return;
        }

        usersPane.classList.remove('hidden');
        rolesPane.classList.add('hidden');
        tabUsersBtn.classList.add('text-slate-900', 'border-b-2', 'border-brand-500');
        tabUsersBtn.classList.remove('text-slate-600', 'border-transparent');
        tabRolesBtn.classList.remove('text-slate-900', 'border-b-2', 'border-brand-500');
        tabRolesBtn.classList.add('text-slate-600', 'border-transparent');
    }

    function showRolesPane() {
        if (!usersPane || !rolesPane || !tabUsersBtn || !tabRolesBtn) {
            return;
        }

        usersPane.classList.add('hidden');
        rolesPane.classList.remove('hidden');
        tabRolesBtn.classList.add('text-slate-900', 'border-b-2', 'border-brand-500');
        tabRolesBtn.classList.remove('text-slate-600', 'border-transparent');
        tabUsersBtn.classList.remove('text-slate-900', 'border-b-2', 'border-brand-500');
        tabUsersBtn.classList.add('text-slate-600', 'border-transparent');
    }

    if (tabUsersBtn) {
        tabUsersBtn.addEventListener('click', showUsersPane);
    }

    if (tabRolesBtn) {
        tabRolesBtn.addEventListener('click', showRolesPane);
    }

    showUsersPane();

    const assignRoleModalBackdrop = document.getElementById('assignRoleModalBackdrop');
    const closeAssignRoleModalBtn = document.getElementById('closeAssignRoleModalBtn');
    const cancelAssignRoleBtn = document.getElementById('cancelAssignRoleBtn');
    const submitAssignRoleBtn = document.getElementById('submitAssignRoleBtn');
    const assignRoleForm = document.getElementById('assignRoleForm');
    const selectedUserNameInput = document.getElementById('selected_user_name');
    const roleIdInput = document.getElementById('role_id');

    let currentUserId = null;

    function openAssignRoleModal(userId, userName) {
        if (!assignRoleModalBackdrop || !selectedUserNameInput || !roleIdInput) {
            return;
        }

        currentUserId = userId;
        selectedUserNameInput.value = userName;
        roleIdInput.value = '';
        assignRoleModalBackdrop.classList.remove('hidden');
    }

    function closeAssignRoleModal() {
        if (!assignRoleModalBackdrop) {
            return;
        }

        assignRoleModalBackdrop.classList.add('hidden');
        currentUserId = null;
    }

    if (closeAssignRoleModalBtn) {
        closeAssignRoleModalBtn.addEventListener('click', closeAssignRoleModal);
    }

    if (cancelAssignRoleBtn) {
        cancelAssignRoleBtn.addEventListener('click', closeAssignRoleModal);
    }

    if (submitAssignRoleBtn) {
        submitAssignRoleBtn.addEventListener('click', () => {
            if (!roleIdInput || !roleIdInput.value) {
                alert('Please select a role');
                return;
            }

            openConfirm(
                'Confirm Role Assignment',
                `Do you want to assign this role to ${selectedUserNameInput.value}?`,
                () => {
                    if (!assignRoleForm) {
                        return;
                    }

                    assignRoleForm.action = `/users/${currentUserId}/role`;
                    assignRoleForm.submit();
                }
            );
        });
    }

    if (assignRoleModalBackdrop) {
        assignRoleModalBackdrop.addEventListener('click', (event) => {
            if (event.target === assignRoleModalBackdrop) {
                closeAssignRoleModal();
            }
        });
    }

    const createRoleModalBackdrop = document.getElementById('createRoleModalBackdrop');
    const openCreateRoleBtn = document.getElementById('openCreateRoleBtn');
    const openCreateRoleBtnPane = document.getElementById('openCreateRoleBtnPane');
    const closeCreateRoleModalBtn = document.getElementById('closeCreateRoleModalBtn');
    const cancelCreateRoleBtn = document.getElementById('cancelCreateRoleBtn');

    function openCreateRoleModal() {
        if (!createRoleModalBackdrop) {
            return;
        }

        createRoleModalBackdrop.classList.remove('hidden');
    }

    function closeCreateRoleModal() {
        if (!createRoleModalBackdrop) {
            return;
        }

        createRoleModalBackdrop.classList.add('hidden');
    }

    if (openCreateRoleBtn) {
        openCreateRoleBtn.addEventListener('click', openCreateRoleModal);
    }

    if (openCreateRoleBtnPane) {
        openCreateRoleBtnPane.addEventListener('click', openCreateRoleModal);
    }

    if (closeCreateRoleModalBtn) {
        closeCreateRoleModalBtn.addEventListener('click', closeCreateRoleModal);
    }

    if (cancelCreateRoleBtn) {
        cancelCreateRoleBtn.addEventListener('click', closeCreateRoleModal);
    }

    if (createRoleModalBackdrop) {
        createRoleModalBackdrop.addEventListener('click', (event) => {
            if (event.target === createRoleModalBackdrop) {
                closeCreateRoleModal();
            }
        });
    }

    const editRoleModalBackdrop = document.getElementById('editRoleModalBackdrop');
    const closeEditRoleModalBtn = document.getElementById('closeEditRoleModalBtn');
    const cancelEditRoleBtn = document.getElementById('cancelEditRoleBtn');
    const editRoleForm = document.getElementById('editRoleForm');
    const editRoleNameInput = document.getElementById('edit_role_name');
    const editRoleDescriptionInput = document.getElementById('edit_role_description');

    function openEditRoleModal(roleId, roleName, roleDescription) {
        if (!editRoleModalBackdrop || !editRoleForm || !editRoleNameInput || !editRoleDescriptionInput) {
            return;
        }

        editRoleForm.action = `/roles/${roleId}`;
        editRoleNameInput.value = roleName || '';
        editRoleDescriptionInput.value = roleDescription || '';
        editRoleModalBackdrop.classList.remove('hidden');
    }

    function closeEditRoleModal() {
        if (!editRoleModalBackdrop) {
            return;
        }

        editRoleModalBackdrop.classList.add('hidden');
    }

    if (closeEditRoleModalBtn) {
        closeEditRoleModalBtn.addEventListener('click', closeEditRoleModal);
    }

    if (cancelEditRoleBtn) {
        cancelEditRoleBtn.addEventListener('click', closeEditRoleModal);
    }

    if (editRoleModalBackdrop) {
        editRoleModalBackdrop.addEventListener('click', (event) => {
            if (event.target === editRoleModalBackdrop) {
                closeEditRoleModal();
            }
        });
    }

    const assignCompanyModalBackdrop = document.getElementById('assignCompanyModalBackdrop');
    const closeAssignCompanyModalBtn = document.getElementById('closeAssignCompanyModalBtn');
    const cancelAssignCompanyBtn = document.getElementById('cancelAssignCompanyBtn');
    const submitAssignCompanyBtn = document.getElementById('submitAssignCompanyBtn');
    const assignCompanyForm = document.getElementById('assignCompanyForm');
    const selectedUserNameCompany = document.getElementById('selected_user_name_company');
    const companyIdInput = document.getElementById('company_id');

    let currentUserIdCompany = null;

    function openAssignCompanyModal(userId, userName) {
        if (!assignCompanyModalBackdrop || !selectedUserNameCompany || !companyIdInput) {
            return;
        }

        currentUserIdCompany = userId;
        selectedUserNameCompany.value = userName;
        companyIdInput.value = '';
        assignCompanyModalBackdrop.classList.remove('hidden');
    }

    function closeAssignCompanyModal() {
        if (!assignCompanyModalBackdrop) {
            return;
        }

        assignCompanyModalBackdrop.classList.add('hidden');
        currentUserIdCompany = null;
    }

    if (closeAssignCompanyModalBtn) {
        closeAssignCompanyModalBtn.addEventListener('click', closeAssignCompanyModal);
    }

    if (cancelAssignCompanyBtn) {
        cancelAssignCompanyBtn.addEventListener('click', closeAssignCompanyModal);
    }

    if (submitAssignCompanyBtn) {
        submitAssignCompanyBtn.addEventListener('click', () => {
            if (!companyIdInput || !companyIdInput.value) {
                alert('Please select a company');
                return;
            }

            openConfirm(
                'Confirm Company Assignment',
                `Do you want to assign this company to ${selectedUserNameCompany.value}?`,
                () => {
                    if (!assignCompanyForm) {
                        return;
                    }

                    assignCompanyForm.action = `/users/${currentUserIdCompany}/company`;
                    assignCompanyForm.submit();
                }
            );
        });
    }

    if (assignCompanyModalBackdrop) {
        assignCompanyModalBackdrop.addEventListener('click', (event) => {
            if (event.target === assignCompanyModalBackdrop) {
                closeAssignCompanyModal();
            }
        });
    }

    window.openAssignRoleModal = openAssignRoleModal;
    window.openAssignCompanyModal = openAssignCompanyModal;
    window.openEditRoleModal = openEditRoleModal;
</script>
