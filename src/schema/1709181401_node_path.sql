ALTER TABLE node ADD n_path VARCHAR(255) NOT NULL, ADD n_sort INT NOT NULL;
CREATE INDEX IDX_857FE845136FC380491FBAAD ON node (n_path, n_sort);
