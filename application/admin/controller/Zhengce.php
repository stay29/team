<?php
namespace app\admin\controller;
use app\common\controller\Backend;
use app\common\model\Category as CategoryModel;
use  app\common\model\Excrpty as Excrpty;
use fast\Tree;
use think\Request;
use  think\DB;
/**
 * 分类管理
 *
 * @icon fa fa-list
 * @remark 用于统一管理网站的所有分类,分类可进行无限级分类,分类类型请在常规管理->系统配置->字典配置中添加
 */
class Zhengce extends Backend
{
    /**
     * @var \app\common\model\Category
     */
    protected $model = null;
    protected $categorylist = [];
    protected $noNeedRight = ['selectpage'];
    protected $searchFields;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('app\common\model\News');
        $tree = Tree::instance();
        $tree->init(collection($this->model->where('keywords = 3 ')->order('weigh desc,createtime desc')->select())->toArray(), 'pid');
        $this->categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $newsdata = [0 => ['type' => 'all', 'name' => __('None')]];

        foreach ($this->categorylist as $k => $v) {
            $newsdata[$v['id']] = $v;
        }
        $this->view->assign("parentList", $newsdata);
        $this->view->assign("type", 3);
    }

    /**
     * 查看
     */
    public function index()
    {
         //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            //把数组中的值赋给一些变量
            // $where=[]; 
         
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $total = $this->model
                ->where('keywords = 3 ')
                ->where($where)
                ->order($sort, $order)
                ->count();
 
            $list = $this->model
                ->where('keywords = 3 ')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
       
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);
 
            return json($result);
        }
        return $this->view->fetch();
    }
    
    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
         if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($row->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                } catch (\think\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }


    /**
     * Selectpage搜索
     *
     * @internal
     */
    public function selectpage()
    {
        return parent::selectpage();
    }
}
