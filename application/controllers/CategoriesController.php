<?php

class CategoriesController extends Zend_Controller_Action
{
 public function init()
 {

 }

 public function indexAction()
 {
  global $sec_ar;//глобальная переменная из index.php

  $front = Zend_Controller_Front::getInstance();
  $item = $front->getRequest()->getParam('item');
  if (!$item)
  {
   $section = $front->getRequest()->getParam('section');
   $cat = $front->getRequest()->getParam('cat');
  }
  else
  {

    try {
        $section = Zend_Registry::get('section');
    }
    catch (Exception $ex) {
        $section=null;
    }

    try {
        $cat = Zend_Registry::get('cat');
    }
    catch (Exception $ex) {
        $cat=null;
    }

  }
  $topid=0;
  if ($section)
  {
     $tmp = $this->check_sectionExists($section);
     $sec_ar[$tmp]['issel']='selected';
     $topid=$sec_ar[$tmp]['id'];
     $this->view->basesection=$tmp;
  }
  $this->view->menuitems = $sec_ar;//список разделов  (книги,канцтовары,ПО,учебная литература)
  $ar="";

  if ($cat)
  {
   $cat = intval($cat);
   $cc = $this->selectExistCat($cat);
   if (!$cc) {
     $ccc=1;
   }
   else
   {
    $ar = $this->section_getSubCats($cat);
   }
  }
  else
  {
   if ($topid!=0)
   {
    $topid = intval($topid);
    $cc = $this->selectExistCat($topid);
    if (!$cc) {
     $ccc=1;
    }
    else
    {
     $ar = $this->section_getSubTopCats($topid);
    }
   }
  }
  if (is_array($ar))
  {
   $ar = $this->categories_ExcludeEmpty($ar);
  }
  $this->view->subcats = $ar;
 }

 private function section_getSubTopCats($topid)
 {
   $res="";
   $db = new Application_Model_DbTable_Catstree();
   $find_ar = $db->getCatsList($topid);
   if ($find_ar) $res = $find_ar;
   return $res;
 }

 private function section_getSubCats($cat)
 {
   $res="";
   $db = new Application_Model_DbTable_Categories();
   $find_ar = $db->getCatsList($cat);
   if ($find_ar) $res = $find_ar;
   return $res;
 }

 private function categories_ExcludeEmpty($ar)
 {
  $res = array();
  $db = new Application_Model_DbTable_Products();
  foreach($ar as $val)
  {
   $cnt = $db->cntProductsInChilds($val['childs']);
   if ($cnt>0)
   {
    $res[] = array('cat'=>$val['cat'],'name'=>$val['name']);
   }
  }
  return $res;
 }

 private function check_sectionExists($section)
 {
  global $sec_ar;
  $tmp='';
  if (array_key_exists($section,$sec_ar)) $tmp = $section; else $tmp='books';
  return $tmp;
 }

 public function catlistAction()
 {
  $cat = $this->_getParam('cat');
  $id=0;
  if ($cat)
  {
   $id=$cat;
  }
  else{
   global $sec_ar;
   $cat = $this->_getParam('section');
   $tmp = $this->check_sectionExists($cat);
   $id=$sec_ar[$tmp]['id'];
  }

  $db = new Application_Model_DbTable_Products();
  $cat = intval($cat);
  $cc = $this->selectExistCat($id);

  if (!$cc) {
  throw new Zend_Controller_Action_Exception('Такой категории у нас нет', 404);
  }
  else
  {
  $this->_forward('get-products-by-cat','products',null,array('cat'=>$id));
  }
 }

 public function categorylegendAction()
 {
  $cat = $this->_getParam('cat');
  $section = $this->_getParam('section');
  $legend_ar = array();
  $this->category_getParents($cat,$legend_ar);
  $this->view->cats=$legend_ar;
  $this->view->section = $section;
  $this->view->item= $this->_getParam('item');
  $this->view->itemname= $this->_getParam('itemname');

 }

 private function category_getParents($cat,&$legend_ar)
 {
  $db = new Application_Model_DbTable_Categories();
  $cc = $db->getCategoryById($cat);
  if(count($cc)>0)
  {
  $parent = $cc[0]['parentId'];
  if ($parent!=0)
  {
   $legend_ar[] = array('id'=>$cat,'name'=>$cc[0]['name']);
   $this->category_getParents($parent,$legend_ar);
  } else return 1;
  }
  else return 1;
 }

 private function selectExistCat($id)
 {
    $db = new Application_Model_DbTable_Categories();
    $cat = $db->getCategoryById($id);
    $res=true; $prods=0;
    if (count($cat)>0)
    {
     $cat = $cat[0]['children'];
     $db2 = new Application_Model_DbTable_Products();
     $prods = $db2->cntProductsInChilds($cat);
     if ($prods==0) $res=false;
    }
    else $res=false;
    return $res;
 }


}

?>