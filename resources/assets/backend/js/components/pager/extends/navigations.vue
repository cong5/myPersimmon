<template>
    <div class="navigation" v-loading="listLoading">
        <el-row :gutter="20">
            <el-col :span="8">
                <div class="grid-content bg-purple">
                    <el-collapse v-model="activeMenuAddCollapse">
                        <el-collapse-item title="添加导航" name="1">
                            <el-form ref="myForm" :rules="myRules" :model="myForm" label-width="100px">
                                <el-form-item label="菜单名称" prop="name">
                                    <el-input size="small" v-model="myForm.name"></el-input>
                                </el-form-item>
                                <el-form-item label="菜单地址" prop="url">
                                    <el-input size="small" v-model="myForm.url"></el-input>
                                </el-form-item>
                                <el-form-item label="排序">
                                    <el-input size="small" v-model="myForm.sorting"></el-input>
                                </el-form-item>
                                <el-form-item>
                                    <el-button type="primary" @click="onSubmit('myForm')">确定</el-button>
                                    <el-button @click="onReset('myForm')">取消</el-button>
                                </el-form-item>
                            </el-form>
                        </el-collapse-item>
                    </el-collapse>
                </div>
            </el-col>
            <el-col :span="16">
                <div class="grid-content bg-purple-light" v-loading="editLoading">
                    <el-card class="box-card">
                        <div slot="header" class="clearfix">
                            <span class="pit-header-title">菜单列表</span>
                            <el-button icon="edit" @click="updateMenu" size="small" type="primary"
                                       style="float: right;margin-top: 5px;">保存
                            </el-button>
                        </div>
                        <el-table :data="navigations" border style="width: 100%"
                                  :default-sort="{prop: 'sorting', order: 'descending'}">
                            <el-table-column label="排序" width="100">
                                <template scope="scope">
                                    <el-input size="mini" v-model="sortList[scope.row.name]" @change="inputSort" placeholder="请输入内容" style="width: 50px;"></el-input>
                                </template>
                            </el-table-column>
                            <el-table-column prop="name" label="菜单名称" min-width="300"></el-table-column>
                            <el-table-column prop="url" label="地址" min-width="300"></el-table-column>
                            <el-table-column inline-template :context="_self"  label="操作">
                                <el-button type="danger" size="small" icon="delete" @click="removeItem(row)"></el-button>
                            </el-table-column>
                        </el-table>
                    </el-card>
                </div>
            </el-col>
        </el-row>

    </div>
</template>
<style type="text/css">
    .navigation {
        padding: 2em;
    }

    .drap-list ul {
        padding: 0;
        margin: 0;
    }

    .drap-list li {
        cursor: pointer;
        position: relative;
    }

    .pit-nav-item {
        border-radius: 4px;
        min-height: 36px;
        background: #99a9bf;
        margin-bottom: 10px;
        padding-left: 10px;
        line-height: 2em;
    }

    i.delete-icon {
        float: right;
        width: 18px;
        height: 18px;
        display: inline-block;
        color: #FFF;
        line-height: 3em;
        font-size: 12px;
        padding-right: 5px;
    }

    .el-card__header {
        padding: 4px 10px;
    }

    .pit-header-title {
        line-height: 36px;
        color: #48576a;
        font-size: 14px;
    }
</style>
<script type="text/ecmascript-6">
    export default{
        data(){
            return {
                myForm: {
                    name: '',
                    url: '',
	                sorting: 0
                },
                myRules: {
                    name: [
                        {required: true, type: "string", message: '请填写菜单名称', trigger: 'blur'},
                        {min: 1, message: '长度大于1个字符', trigger: 'blur'}
                    ],
                    url: [
                        {required: true, type: "string", message: '请填写url地址', trigger: 'blur'},
                        {min: 1, message: '长度大于1个字符', trigger: 'blur'}
                    ]
                },
                navigations: [],
                sortList: {},
                activeMenuAddCollapse: '1',
                listLoading: false,
                editLoading: false,
                showHeader: false,
            }
        },
        methods: {
            onSubmit: function (myForm) {
            	var _this = this;
	            _this.$refs[myForm].validate((valid) => {
                    if (!valid) {
                        console.log('myForm valid error.');
                        return false;
                    }
		            _this.navigations.splice(-1,0,this.myForm);
		            _this.myForm = {
			            name: '',
                        url: '',
                        sorting: 0
		            };
		            //console.log(_this.navigations);
                });
            },
            onReset: function (myForm) {
                this.myForm = {
	                name: '',
	                url: '',
                    sorting: 0
                };
            },
            getData: function () {
                let _this = this;
                _this.listLoading = true;
                _this.axios.get('/navigations').then(function (response) {
                    let res = response.data;
                    if (res != false) {
                        res.sort(function(x, y){
                            return x.sorting > y.sorting ? 1:-1;
                        });
                        _this.navigations = res;
                        //获取排序值
                        if (_this.navigations.length > 0) {
                            for (let index in _this.navigations) {
                                _this.sortList[_this.navigations[index]['name']] = _this.navigations[index]['sorting'];
                            }
                            //console.log( _this.sortList);
                        }
                    } else {
                        console.log('数据获取失败或者数据为空。')
                    }
                    _this.listLoading = false;
                }).catch(function (error) {
                    console.log(error);
                    _this.listLoading = false;
                });
            },
            removeItem: function (item) {
                var _this = this;
                _this.$confirm('确认删除该记录吗?', '提示', {
                    //type: 'warning'
                }).then(() => {
                    for (let index in _this.navigations) {
                        if (_this.navigations[index].name == item.name) {
                            _this.navigations.splice(index, 1);
                        }
                    }
                }).catch(() => {
                    _this.listLoading = false;
                });
            },
            inputSort: function (value) {
                //console.log(this.sortList);
                for (let key in this.sortList) {
                    for (let index in this.navigations) {
                        if (this.navigations[index].name == key) {
                            this.navigations[index]['sorting'] = parseInt(this.sortList[key]);
                        }
                    }
                }
                this.navigations.sort(function(x, y){
                    return x.sorting > y.sorting ? 1:-1;
                });
            },
            updateSort: function () {
                if (this.navigations.lenght > 0) {
                    //this.navigations.sort((a, b) => parseInt(a.sorting) - parseInt(b.sorting));
                    this.navigations.sort(function(x, y){
                        return x.b > y.b ? 1:-1;
                    });
                    console.log(this.navigations);
                }
            },
            updateMenu: function () {
                let _this = this;
                _this.editLoading = true;
                _this.axios.put('/navigations/update', _this.navigations).then(function (response) {
                    let res = response.data;
                    if (res != false) {
                        _this.$message({
                            message: '更新成功',
                            type: 'success',
                            duration: 3 * 1000
                        });
                    } else {
                        _this.$message({
                            message: '更新失败',
                            type: 'error',
                            duration: 3 * 1000
                        });
                    }
                    _this.editLoading = false;
                }).catch(function (error) {
                    console.log(error);
                    _this.editLoading = false;
                });
            },
            orderBy:function(name){
                return function(o, p){
                    var a, b;
                    if (typeof o === "object" && typeof p === "object" && o && p) {
                        a = o[name];
                        b = p[name];
                        if (a === b) {
                            return 0;
                        }
                        if (typeof a === typeof b) {
                            return a < b ? -1 : 1;
                        }
                        return typeof a < typeof b ? -1 : 1;
                    }
                    else {
                        throw ("error");
                    }
                }
            }
        },
        mounted() {
            this.getData();
        }
    }
</script>
