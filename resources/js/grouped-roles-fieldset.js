document.addEventListener('alpine:init', function () {
    Alpine.data('groupedRolesFieldset', function (config) {
        return {
            selected: config.selected || [],
            groups: config.groups || [],
            syncing: false,

            init: function () {
                var self = this;

                this.$nextTick(function () {
                    self.syncGroupCheckboxes();
                });
            },

            allRoleNames: function () {
                var names = [];

                this.groups.forEach(function (group) {
                    group.roles.forEach(function (name) {
                        names.push(name);
                    });
                });

                return names;
            },

            isSelected: function (name) {
                return this.selected.indexOf(name) !== -1;
            },

            toggleRole: function (event) {
                if (this.syncing) {
                    return;
                }

                var name = event.target.value;

                if (!name) {
                    return;
                }

                if (event.target.checked) {
                    if (!this.isSelected(name)) {
                        this.selected.push(name);
                    }
                } else {
                    this.selected = this.selected.filter(function (roleName) {
                        return roleName !== name;
                    });
                }

                this.syncGroupCheckboxes();
            },

            isAllSelected: function () {
                var all = this.allRoleNames();

                if (all.length === 0) {
                    return false;
                }

                var self = this;

                return all.every(function (name) {
                    return self.isSelected(name);
                });
            },

            isSomeSelected: function () {
                return this.selected.length > 0 && !this.isAllSelected();
            },

            toggleAll: function (event) {
                if (this.syncing) {
                    return;
                }

                var checkbox = event.target;

                if (checkbox.indeterminate || checkbox.checked) {
                    this.selected = this.allRoleNames().slice();
                } else {
                    this.selected = [];
                }

                this.syncRoleCheckboxes();
                this.syncGroupCheckboxes();
            },

            groupRoleNames: function (groupKey) {
                var group = this.groups.find(function (item) {
                    return item.key === groupKey;
                });

                return group ? group.roles : [];
            },

            isGroupAllSelected: function (groupKey) {
                var names = this.groupRoleNames(groupKey);

                if (names.length === 0) {
                    return false;
                }

                var self = this;

                return names.every(function (name) {
                    return self.isSelected(name);
                });
            },

            isGroupSomeSelected: function (groupKey) {
                var names = this.groupRoleNames(groupKey);
                var self = this;
                var selectedCount = 0;

                names.forEach(function (name) {
                    if (self.isSelected(name)) {
                        selectedCount++;
                    }
                });

                return selectedCount > 0 && !this.isGroupAllSelected(groupKey);
            },

            toggleGroup: function (groupKey, event) {
                if (this.syncing) {
                    return;
                }

                var checkbox = event.target;
                var names = this.groupRoleNames(groupKey);
                var self = this;

                if (checkbox.indeterminate || checkbox.checked) {
                    names.forEach(function (name) {
                        if (!self.isSelected(name)) {
                            self.selected.push(name);
                        }
                    });
                } else {
                    this.selected = this.selected.filter(function (name) {
                        return names.indexOf(name) === -1;
                    });
                }

                this.syncRoleCheckboxes();
                this.syncGroupCheckboxes();
            },

            syncRoleCheckboxes: function () {
                var self = this;

                this.syncing = true;

                this.$root.querySelectorAll('ui-checkbox[name="roles[]"]').forEach(function (checkbox) {
                    checkbox.checked = self.isSelected(checkbox.value);
                });

                this.syncing = false;
            },

            syncGroupCheckboxes: function () {
                var self = this;

                this.syncing = true;

                var globalAll = this.$root.querySelector('[data-select-all="global"] ui-checkbox');

                if (globalAll) {
                    this.applySelectAllState(
                        globalAll,
                        this.isAllSelected(),
                        this.isSomeSelected(),
                    );
                }

                this.groups.forEach(function (group) {
                    var groupAll = self.$root.querySelector('[data-select-all="group"][data-group-key="' + group.key + '"] ui-checkbox');

                    if (!groupAll) {
                        return;
                    }

                    self.applySelectAllState(
                        groupAll,
                        self.isGroupAllSelected(group.key),
                        self.isGroupSomeSelected(group.key),
                    );
                });

                this.syncing = false;
            },

            applySelectAllState: function (checkbox, allSelected, someSelected) {
                checkbox.checked = allSelected;
                checkbox.indeterminate = someSelected;
            },
        };
    });
});
