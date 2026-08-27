document.addEventListener('alpine:init', function () {
    Alpine.data('groupedSpecificationsFieldset', function (config) {
        return {
            selectedGroups: config.selectedGroups || [],
            selectedValues: config.selectedValues || [],
            groups: config.groups || [],
            syncing: false,

            init: function () {
                var self = this;

                this.$nextTick(function () {
                    self.syncGroupCheckboxes();
                    self.syncValueCheckboxes();
                });
            },

            allGroupKeys: function () {
                return this.groups.map(function (group) {
                    return group.key;
                });
            },

            isGroupSelected: function (groupKey) {
                return this.selectedGroups.indexOf(groupKey) !== -1;
            },

            isValueSelected: function (valueId) {
                var id = String(valueId);

                return this.selectedValues.indexOf(id) !== -1;
            },

            toggleGroupSelection: function (groupKey, event) {
                if (this.syncing) {
                    return;
                }

                var checkbox = event.target;

                if (checkbox.indeterminate || checkbox.checked) {
                    if (!this.isGroupSelected(groupKey)) {
                        this.selectedGroups.push(groupKey);
                    }
                } else {
                    this.selectedGroups = this.selectedGroups.filter(function (key) {
                        return key !== groupKey;
                    });

                    this.clearGroupValues(groupKey);
                }

                this.syncGroupSelectionCheckboxes();
                this.syncValueCheckboxes();
            },

            toggleAllGroups: function (event) {
                if (this.syncing) {
                    return;
                }

                var checkbox = event.target;

                if (checkbox.indeterminate || checkbox.checked) {
                    this.selectedGroups = this.allGroupKeys().slice();
                } else {
                    this.selectedGroups = [];
                    this.selectedValues = [];
                }

                this.syncGroupSelectionCheckboxes();
                this.syncValueCheckboxes();
            },

            isAllGroupsSelected: function () {
                var all = this.allGroupKeys();

                if (all.length === 0) {
                    return false;
                }

                var self = this;

                return all.every(function (key) {
                    return self.isGroupSelected(key);
                });
            },

            isSomeGroupsSelected: function () {
                return this.selectedGroups.length > 0 && !this.isAllGroupsSelected();
            },

            groupValueIds: function (groupKey) {
                var group = this.groups.find(function (item) {
                    return item.key === groupKey;
                });

                if (!group) {
                    return [];
                }

                return group.values.map(function (value) {
                    return String(value.id);
                });
            },

            clearGroupValues: function (groupKey) {
                var ids = this.groupValueIds(groupKey);
                var self = this;

                this.selectedValues = this.selectedValues.filter(function (valueId) {
                    return ids.indexOf(valueId) === -1;
                });
            },

            toggleValue: function (event) {
                if (this.syncing) {
                    return;
                }

                var valueId = event.target.value;

                if (!valueId) {
                    return;
                }

                if (event.target.checked) {
                    if (!this.isValueSelected(valueId)) {
                        this.selectedValues.push(valueId);
                    }
                } else {
                    this.selectedValues = this.selectedValues.filter(function (id) {
                        return id !== valueId;
                    });
                }

                this.syncGroupValueCheckboxes();
            },

            isGroupAllValuesSelected: function (groupKey) {
                var ids = this.groupValueIds(groupKey);

                if (ids.length === 0) {
                    return false;
                }

                var self = this;

                return ids.every(function (id) {
                    return self.isValueSelected(id);
                });
            },

            isGroupSomeValuesSelected: function (groupKey) {
                var ids = this.groupValueIds(groupKey);
                var self = this;
                var selectedCount = 0;

                ids.forEach(function (id) {
                    if (self.isValueSelected(id)) {
                        selectedCount++;
                    }
                });

                return selectedCount > 0 && !this.isGroupAllValuesSelected(groupKey);
            },

            toggleAllValuesInGroup: function (groupKey, event) {
                if (this.syncing) {
                    return;
                }

                var checkbox = event.target;
                var ids = this.groupValueIds(groupKey);
                var self = this;

                if (checkbox.indeterminate || checkbox.checked) {
                    ids.forEach(function (id) {
                        if (!self.isValueSelected(id)) {
                            self.selectedValues.push(id);
                        }
                    });
                } else {
                    this.selectedValues = this.selectedValues.filter(function (id) {
                        return ids.indexOf(id) === -1;
                    });
                }

                this.syncValueCheckboxes();
            },

            syncGroupSelectionCheckboxes: function () {
                var self = this;

                this.syncing = true;

                var globalAll = this.$root.querySelector('[data-select-all="global-groups"] ui-checkbox');

                if (globalAll) {
                    this.applySelectAllState(
                        globalAll,
                        this.isAllGroupsSelected(),
                        this.isSomeGroupsSelected(),
                    );
                }

                this.groups.forEach(function (group) {
                    var groupCheckbox = self.$root.querySelector('[data-select-group="' + group.key + '"] ui-checkbox');

                    if (!groupCheckbox) {
                        return;
                    }

                    groupCheckbox.checked = self.isGroupSelected(group.key);
                    groupCheckbox.indeterminate = false;
                });

                this.syncing = false;
            },

            syncValueCheckboxes: function () {
                var self = this;

                this.syncing = true;

                this.$root.querySelectorAll('ui-checkbox[name="specification_value_ids[]"]').forEach(function (checkbox) {
                    checkbox.checked = self.isValueSelected(checkbox.value);
                });

                this.syncGroupValueCheckboxes();

                this.syncing = false;
            },

            syncGroupValueCheckboxes: function () {
                var self = this;

                this.syncing = true;

                this.groups.forEach(function (group) {
                    var groupAll = self.$root.querySelector('[data-select-all="group-values"][data-group-key="' + group.key + '"] ui-checkbox');

                    if (!groupAll) {
                        return;
                    }

                    self.applySelectAllState(
                        groupAll,
                        self.isGroupAllValuesSelected(group.key),
                        self.isGroupSomeValuesSelected(group.key),
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
