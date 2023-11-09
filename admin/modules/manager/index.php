<? require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/theme/default/header.php"); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="35" class="title_text">Файловый менеджер</td>
    </tr>
    <tr>
    <td>
<?php

require_once 'ckfinder.php' ;

// You can use the "CKFinder" class to render CKFinder in a page:
$finder = new CKFinder() ;
$finder->BasePath = '/admin/modules/manager/' ;	// The path for the installation of CKFinder (default = "/ckfinder/").
$finder->SelectFunction = 'ShowFileInfo' ;
// The default height is 400.
$finder->Height = 600;
$finder->Create() ;

// It can also be done in a single line, calling the "static"
// Create( basePath, width, height, selectFunction ) function:
// CKFinder::CreateStatic( '../../', null, null, 'ShowFileInfo' ) ;

?>
</td>
</tr>
</table>
<? require_once($_SERVER['DOCUMENT_ROOT']."/admin/modules/theme/default/footer.php")?>