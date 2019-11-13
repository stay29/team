define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'qualification/index' + location.search,
                    add_url: 'qualification/add',
                    edit_url: 'qualification/edit',
                    del_url: 'qualification/del',
                    multi_url: 'qualification/multi',
                    table: 'qualification',
                    commonSearch: true,
                    search: false,
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'name', title: __('Name')},
                        {field: 'sex', title: __('Sex'), searchList: {"男":__('男'),"女":__('女')}, formatter: Table.api.formatter.normal},
                        {field: 'province', title: __('Province'),searchList:{"110000":__('北京市'),"120000":__('天津市'),"130000":__('河北省'),"140000":__('山西省'),"150000":__('内蒙古自治区'),"210000":__('辽宁省'),"220000":__('吉林省'),"230000":__('黑龙江省'),"310000":__('上海市'),"320000":__('江苏省'),"330000":__('浙江省'),"340000":__('安徽省'),"350000":__('福建省'),"360000":__('江西省'),"370000":__('山东省'),"410000":__('河南省'),"420000":__('河北省'),"430000":__('湖南省'),"440000":__('广东省'),"450000":__('广西壮族自治区'),"460000":__('海南省'),"500000":__('重庆市'),"510000":__('四川省'),"520000":__('贵州省'),"530000":__('云南省'),"540000":__('西藏自治区'),"610000":__('陕西省'),"620000":__('甘肃省'),"630000":__('青海省'),"640000":__('宁夏回族自治区'),"650000":__('新疆维吾尔自治区'),"710000":__('台湾省'),"810000":__('香港特别行政区'),"820000":__('门特别行政区')} ,formatter: Table.api.formatter.normal },
                        {field: 'birth', title: __('Birth'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'class', title: __('报考等级'), searchList: {"1":__('一级'),"2":__('二级'),"3":__('三级'),"4":__('四级')}, formatter: Table.api.formatter.normal},
                        // {field: 'certificate_type', title: __('证件类型'), searchList: {"2":__('身份证'),"3":__('护照')}, formatter: Table.api.formatter.normal},
                        // {field: 'certificate_number', title: __('Certificate_number')},
                        {field: 'music_school', title: __('Music_school'), searchList: {"4":__('钢琴专业在校'),"5":__('在职钢琴教师'),"6":__('其他')}, formatter: Table.api.formatter.normal},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'status', title: __('Status'), searchList: {"0":__('审核中'),"1":__('审核通过'),"2":__('审核不通过')}, formatter: Table.api.formatter.normal},
                          {field: 'operate', title: __('Operate'), table: table, buttons: [
                                {name: 'goods', text: '详情', title: '详情', icon: 'fa fa-list', classname: 'btn btn-xs btn-primary btn-dialog', url: 'qualification/details'},
                                {name: 'gyGoods', text: '审核', title: '审核', icon: 'fa fa-flash', classname: 'btn btn-xs btn-primary btn-dialog', url: 'qualification/audit'},
                                {name: 'orders', text: '成绩', title: '成绩', icon: 'fa fa-gg-circle', classname: 'btn btn-xs btn-primary btn-dialog', url: 'qualification/results'},
                            ], operate:false, formatter: Table.api.formatter.buttons}
                    ]
                ]
            }); 
        //    Fast.api.open("qualification/details", "详情", {
        //      callback:function(value){
        // // 在这里可以接收弹出层中使用`Fast.api.close(data)`进行回传数据
        //          }
        //       }); 
            // 为表格绑定事件
            Table.api.bindevent(table);

        },
         //这里的有用，按照官方的cope改一下
        details: function () {
            return  false;
           Controller.api.bindevent();
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
