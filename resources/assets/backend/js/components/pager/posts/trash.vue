<template>
    <div class="pit-content">
        <div class="pit-action-btn">
            <el-button type="primary" @click="handleDistory('multi',{})" icon="delete">删除</el-button>
            <el-select v-model="category_id" clearable @change="filterCategory" placeholder="请选择">
                <el-option
                        v-for="item in categorys"
                        :label="item.category_name"
                        :value="item.id">
                </el-option>
            </el-select>
            <el-input v-model="q" placeholder="请输入内容" icon="search" style="width: 200px"
                      :on-icon-click="searchBtn"></el-input>
        </div>

        <template>
            <el-table :data="listData" v-loading="listLoading" style="width: 100%"
                      @selection-change="handleSelectionChange">
                <el-table-column type="selection" width="55"></el-table-column>
                <el-table-column sortable label="标题" min-width="500">
                    <template scope="scope">
                        <router-link :to="{ path: '/posts/edit/'+ scope.row.id}" class="links">{{ scope.row.title }}
                        </router-link>
                    </template>
                </el-table-column>
                <el-table-column label="分类" width="200">
                    <template scope="scope">
                        <span v-if="scope.row.categories">{{ scope.row.categories.category_name }}</span>
                    </template>
                </el-table-column>
                <el-table-column sortable label="标签" width="400">
                    <template scope="scope">
                        <el-tag v-for="tag in scope.row.tags" type="primary">
                            {{tag.tags_name}}
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="created_at" :formatter="formatterDate" sortable label="日期"
                                 width="250"></el-table-column>
                <el-table-column inline-template :context="_self" label="操作" width="150">
                    <span class="fr">
                        <el-button size="small" @click="handleRestore(row)"><i class="fa fa-retweet" aria-hidden="true"></i></el-button>
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

    </div>
</template>
<style type="text/css">
    .links {
        text-decoration: none;
        color: #1f2d3d;
    }
</style>
<script type="text/ecmascript-6">
    export default{
        data(){
            return {
                listData: [],
                category_id: '',
                categorys: [],
                currentPage: 1,
                total: 0,
                pageSize: 20,
                listLoading: false,
                checkedAll: [],
                q: ''
            }
        },
        methods: {
            formatterDate: function (row, column) {
                if (row.updated_at == '') {
                    return '';
                }
                return this.util.formatDate(row.updated_at);
            },
            filterCategory: function (value) {
                this.getData();
            },
            searchBtn: function (event) {
                this.getData();
            },
            getData: function () {
                let _this = this;
                _this.listLoading = true;
                let query = {
                    rows: _this.pageSize,
                    category_id: _this.category_id,
                    q: _this.q
                };

                //console.log(query);
                _this.axios.get('/trash', {params: query}).then(function (response) {
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
            handleRestore: function (row) {
                let _this = this;
                _this.$confirm('确认恢复这篇文章吗?', '提示', {
                    //type: 'warning'
                }).then(() => {
                    _this.listLoading = true;
                    _this.axios.put('/trash/update', {'ids': [row.id]}).then(function (response) {
                        _this.listLoading = false;
                        let res = response.data;
                        _this.$message({
                            message: res.status == 'success' ? '恢复成功' : '恢复失败',
                            type: res.status
                        });
                        _this.util.removeByValue(_this.listData, row.id);
                    }).catch(function (error) {
                        console.log(error);
                    });
                }).catch(() => {
                    _this.listLoading = false;
                });
            },
            handleDistory: function (type, row) {
                let _this = this, idsParam = {};
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
                        let ids = _this.util.getIdByArr(_this.checkedAll);
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
                    _this.axios.delete('/trash/destroy', {data: idsParam}).then(function (response) {
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
            handleSelectionChange(val) {
                this.checkedAll = val;
            },
            getCategorys: function () {
                let _this = this;
                _this.axios.get('/categorys', {
                    params: {
                        rows: 999
                    }
                }).then(function (response) {
                    let res = response.data;
                    if (res != false) {
                        res.data.splice(0, 0, {id: 0, category_name: '顶级分类', hidden: true, category_parent: 0});
                        _this.categorys = res.data;
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
            setTopCategorys: function () {
                var categorys = this.listData.concat();
                categorys.splice(0, 0, {id: 0, category_name: '顶级分类', hidden: true, category_parent: 0});
                this.categorys = categorys;
            }
        },
        mounted() {
            this.getCategorys();
            this.getData();
        }
    }
</script>
