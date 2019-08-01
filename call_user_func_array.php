<?php


//call_user_func_array 的用法


//用法 -1  demo

class A 
{

/*
     *
     *     
     *
     *
     */
    public function postFile(Request $req)
    {    
        $this->dataVerify->checkPostFile($req);
        $funs = array(
            '_removeZero',
            '_postGroupLogo',
            '_postGroupHandbook',
            '_postGroupNewsLogo',
            '_postGroupVideoAva',
            '_postGroupVideoCover',
            '_postGroupVideo',
        );
        // 方法 - 1
        // $this->{$default}{
        // }
        // $a = $this->{$rtn[$req->type]}();
        // return $a;
        // 方法 - 2
        // 通过前端传递不同的 type 然后获取到不同的 funs 里面对应的函数名，调用相关函数
        return $this->setReturnVars(ApiConsts::SUCCESS, 
            call_user_func_array(array($this,$funs[$req->type]),array($req->group_id,$req->type)), 'DATA_GET_SUCCESS');
    }
    private function _removeZero($req = '')
    {

    }
    private function _postGroupLogo($req)
    {
        $this->dataVerify->checkPostFileGroup($req);
        return $this->policy->setUploadGroupLogoDir($req->group_id, $req->type);
    }
    private function _postGroupHandbook($req)
    {
        $this->dataVerify->checkPostFileGroup($req);
        return $this->policy->setUploadGroupLogoDir($req->group_id, $req->type);
    }
    private function _postGroupNewsLogo($req)
    {
        $this->dataVerify->checkPostFileGroupNewsId($req);
        return $this->policy->setUploadGroupLogoDir($req->group_id, $req->type);
    }
    private function _postGroupVideoAva($req)
    {
        $this->dataVerify->checkPostFileGroupVideoId($req);
        return $this->policy->setUploadGroupLogoDir($req->group_id, $req->type);
    }
    private function _postGroupVideoCover($req)
    {
        $this->dataVerify->checkPostFileGroupVideoId($req);
        return $this->policy->setUploadGroupLogoDir($req->group_id, $req->type);
    }
    private function _postGroupVideo($req)
    {
        $this->dataVerify->checkPostFileGroupVideoId($req);
        return $this->policy->setUploadGroupLogoDir($req->group_id, $req->type);
    }
}