ssh-keygen -t ed25519 -f ansible/roles/users/files/admin-key -N "" -C "admin-user@$(hostname)"
ssh-keygen -t ed25519 -f ansible/roles/users/files/deploy-key -N "" -C "deploy-user@$(hostname)"


