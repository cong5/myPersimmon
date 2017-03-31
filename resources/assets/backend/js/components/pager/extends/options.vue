<template>
    <div class="pit-content">
        <div class="pit-action-btn">
            <el-button type="primary" @click="handleCreate" icon="plus">新增</el-button>
            <el-button type="primary" @click="handleDistory('multi',{})" icon="delete">删除</el-button>
        </div>

        <template>
            <el-table :data="listData" v-loading="listLoading" style="width: 100%"
                      @selection-change="handleSelectionChange">
                <el-table-column type="selection" width="55"></el-table-column>
                <el-table-column prop="option_title" min-width="200" sortable label="配置项说明"></el-table-column>
                <el-table-column prop="option_name" sortable min-width="200" label="配置项名称"></el-table-column>
                <el-table-column prop="option_group" sortable min-width="200" label="配置分组"></el-table-column>
                <el-table-column prop="option_remark" sortable min-width="200" label="配置项备注"></el-table-column>
                <el-table-column prop="created_at" sortable :formatter="formatterDate" label="日期"
                                 width="200"></el-table-column>
                <el-table-column inline-template :context="_self" label="操作" width="150">
                    <span>
                        <el-button size="small" icon="edit" @click="handleEdit(row)"></el-button>
                        <el-button type="danger" size="small" icon="delete"
                                   @click="handleDistory('one',row)"></el-button>
                    </span>
                </el-table-column>
            </el-table>
        </template>

        <el-pagination
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
                :current-page="currentPage"
                :page-sizes="[20, 50, 80, 100, 200]"
                :page-size="pageSize"
                layout="sizes, prev, pager, next"
                :total="total">
        </el-pagination>

        <el-dialog :title="myFormTitle" v-model="editFormVisible">
            <div class="pit-dialog-edit-form" v-loading="editFormLoading">
                <el-form ref="myForm" :rules="myRules" class="myForm" label-width="100px" :model="myForm"
                         style="width: 80%">
                    <el-form-item label="配置项说明" prop="option_title">
                        <el-input v-model="myForm.option_title" auto-complete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="配置项名称" prop="option_name">
                        <el-input v-model="myForm.option_name" auto-complete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="配置项值">
                        <el-input :type="myForm.data_type" :autosize="myForm.data_type == 'textarea'"
                                  v-model="myForm.option_value" auto-complete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="配置分组">
                        <el-input v-model="myForm.option_group" auto-complete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="配置项备注">
                        <el-input type="textarea" :autosize="{ minRows: 3, maxRows: 5}" v-model="myForm.option_remark"
                                  auto-complete="off"></el-input>
                    </el-form-item>
                    <el-form-item v-if="myForm.id">
                        <el-input v-model="myForm.id" style="display: none;"></el-input>
                    </el-form-item>
                </el-form>
                <div slot="footer" class="dialog-footer">
                    <el-button @click="closeForm('myForm')">取 消</el-button>
                    <el-button type="primary" @click="submitMyForm('myForm')">确 定</el-button>
                </div>
            </div>
        </el-dialog>

    </div>
</template>
<style type="text/css">

</style>
<script type="text/ecmascript-6">
    export default{
        data(){
            return {
                listData: [],
                currentPage: 1,
                total: 0,
                pageSize: 20,
                myForm: {
                    id: 0,
                    option_title: '',
                    option_name: '',
                    option_value: '',
                    option_group: '',
                    option_remark: ''
                },
                myRules: {
                    option_title: [
                        {required: true, type: "string", message: '请填写配置项说明', trigger: 'blur'}
                    ],
                    option_name: [
                        {required: true, type: "string", message: '请填写配置项名称', trigger: 'blur'},
                        {pattern: /^[a-zA-Z0-9_]+$/, message: '只允许英文或者拼音,横杠(-)', trigger: 'blur'}
                    ]
                },
                editFormVisible: false,
                editFormLoading: false,
                listLoading: false,
                myFormTitle: '编辑',
                checkedAll: []
            }
        },
        methods: {
            formatterDate: function (row, column) {
                if (row.updated_at == '') {
                    return '';
                }
                return this.util.formatDate(row.updated_at);
            },
            getData: function () {
                var _this = this;
                _this.listLoading = true;
                _this.axios.get('/options', {
                    params: {
                        rows: this.pageSize
                    }
                }).then(function (response) {
                    let res = response.data;
                    if (res != false) {
                        _this.listData = res.data;
                        _this.total = res.total;
                        _this.currentPage = res.current_page;
                        _this.listLoading = false;
                    } else {
                        _this.$message({
                            message: '数据获取失败',
                            type: 'error',
                            duration: 3 * 1000
                        });
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            handleSizeChange(val) {
                //console.log(`每页 ${val} 条`);
                this.pageSize = val;
                this.getData();
            },
            handleCurrentChange(val) {
                this.currentPage = val;
                //console.log(`当前页: ${val}`);
            },
            handleCreate: function () {
                var _this = this;
                _this.myFormTitle = '新增';
                _this.myForm.id = 0;
                _this.editFormVisible = true;
            },
            handleEdit: function (row) {
                var _this = this;
                _this.editFormLoading = true;
                _this.myFormTitle = '编辑';
                _this.editFormVisible = true;
                _this.axios.get('/options/' + row.id).then(function (response) {
                    let res = response.data;
                    if (res != false) {
                        _this.myForm = res;
                    } else {
                        _this.$message({
                            message: '数据获取失败',
                            type: 'error'
                        });
                    }
                    _this.editFormLoading = false;
                }).catch(function (error) {
                    console.log(error);
                    _this.editFormLoading = false;
                });
            },
            handleDistory: function (type, row) {
                var _this = this, idsParam = {};
                switch (type) {
                    case 'one':
                        if (parseInt(row.id) <= 0) {
                            _this.$message({
                                message: '请选择需要删除的数据',
                                type: 'warning'
                            });
                            return false;
                        }
                        idsParam = {ids: [row.id]};
                        break;
                    case 'multi':
                        var ids = _this.util.getIdByArr(_this.checkedAll);
                        if (ids.length <= 0) {
                            _this.$message({
                                message: '请选择需要删除的数据',
                                type: 'warning'
                            });
                            return false;
                        }
                        idsParam = {ids: ids};
                        break;
                    default:
                        break;
                }

                _this.$confirm('确认删除该记录吗?', '提示', {
                    //type: 'warning'
                }).then(() => {
                    _this.listLoading = true;
                    _this.axios.delete('/options/destroy', {data: idsParam}).then(function (response) {
                        _this.listLoading = false;
                        let res = response.data;
                        _this.$message({
                            message: res.status == 'success' ? '删除成功' : '删除失败',
                            type: res.status
                        });
                        if (type == 'one') {
                            _this.util.removeByValue(_this.listData, row.id);
                        } else {
                            for (var index in _this.checkedAll) {
                                _this.util.removeByValue(_this.listData, _this.checkedAll[index].id);
                            }
                        }

                    }).catch(function (error) {
                        console.log(error);
                    });
                }).catch(() => {
                    _this.listLoading = false;
                });
            },
            submitMyForm: function (myForm) {
                var _this = this;
                _this.$refs[myForm].validate((valid) => {
                    if (!valid) {
                        console.log('myForm valid error.');
                        return false;
                    }

                    if (_this.myForm.id > 0) {
                        _this.axios.put('/options/update', _this.myForm).then(function (response) {
                            let res = response.data;
                            _this.$message({
                                message: res.status == 'success' ? '编辑成功' : '编辑失败',
                                type: res.status
                            });
                            if (res.status == 'success') {
                                _this.closeForm('myForm');
                                _this.getData();
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    } else {
                        _this.axios.post('/options', _this.myForm).then(function (response) {

                            let res = response.data;
                            if (res.status == 'success') {
                                _this.closeForm('myForm');
                                _this.getData();
                            }
                            _this.$message({
                                message: res.status == 'success' ? '新增成功' : '新增失败',
                                type: res.status
                            });
                        }).catch(function (error) {
                            if (error.response) {
                                if (error.response.status == 422) {
                                    for (var index in error.response.data) {
                                        _this.$notify({
                                            title: '警告',
                                            message: error.response.data[index][0],
                                            type: 'warning'
                                        });
                                    }
                                }
                            } else {
                                console.log(error);
                            }
                        });
                    }
                });
            },
            closeForm: function (myForm) {
                this.editFormVisible = false;
                this.$refs[myForm].resetFields();
                this.myForm = {
                    id: 0,
                    name: '',
                    url: '',
                    logo: '',
                    group: '',
                };
                console.log('closeForm');
            },
            handleSelectionChange(val) {
                this.checkedAll = val;
            }
        },
        watch: {},
        mounted() {
            this.getData();
        }
    }
</script>
