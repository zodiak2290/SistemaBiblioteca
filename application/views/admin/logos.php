<?php 
    $contador=0; foreach ($datos as $row) {   
            $name=$row->nombre;
           $cuentaempleado=$row->cuenta;
           $email=$row->email;
 }
 if($rol==8){
?> 
<hr/><hr/>
<link rel="stylesheet" href="<?php echo site_url(); ?>css/libros/carga.css" type="text/css" />
<div id="page-wrapper" class="col-md-11 text-center" ng-app="setting" ng-controller="setcontroller">
              <div id="carga" class="pull-left windows8 container col-lg-10" hidden>
                        <div class="wBall" id="wBall_1" style="width:55%;height: 100px;">
                        <div class="wInnerBall">
                        </div>
                        </div>
                        <div class="wBall" id="wBall_2" style="width:55%;height: 100px;">
                        <div class="wInnerBall">
                        </div>
                        </div>
                        <div class="wBall" id="wBall_3" style="width:55%;height: 100px;">
                        <div class="wInnerBall">
                        </div>
                        </div>
                        <div class="wBall" id="wBall_4" style="width:55%;height: 100px;">
                        <div class="wInnerBall">
                        </div>
                        </div>
                        <div class="wBall" id="wBall_5" style="width:55%;height: 100px;">
                        <div class="wInnerBall">
                        </div>
                        </div>
              </div> 
<div class="panel panel-default col-lg-8" id="images">
            <div class="panel-body" >                 
              <div class="row" >
                <div class="col-sm-6 col-md-6" >
                  <div class="thumbnail">
                    <img src="<?php echo site_url(); ?>images/logo/logooax.png" alt="..." id="logooax" class=" img-responsive">
                    <div class="caption">
                      <p><a ng-click="cambiar('logooax')" class="btn btn-primary" role="button">Cambiar</a></p>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 col-md-6"  >
                  <div class="thumbnail">
                    <img src="<?php echo site_url(); ?>images/logo/oaxaca.png" alt="..." id="oaxaca" class=" img-responsive" >
                    <div class="caption">
                      <p><a ng-click="cambiar('oaxaca')" class="btn btn-primary" role="button">Cambiar</a></p>
                    </div>
                  </div>
                </div>
    <hr>
    <stong>Enlaces</strong>
                <div class="col-sm-12 col-md-12"  >
                  <div class="thumbnail">
                    <img src="<?php echo site_url(); ?>images/logo/enlace1.png" alt="Enlace" id="enlace1" class=" img-responsive" >
                    <div class="caption">
                      <p><a ng-click="cambiar('enlace1')" class="btn btn-primary" role="button">Cambiar</a></p>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12 col-md-12"  >
                  <div class="thumbnail">
                    <img src="<?php echo site_url(); ?>images/logo/enlace2.png" alt="Enlace" id="enlace2" class=" img-responsive" >
                    <div class="caption">
                      <p><a ng-click="cambiar('enlace2')" class="btn btn-primary" role="button">Cambiar</a></p>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12 col-md-12"  >
                  <div class="thumbnail">
                    <img src="<?php echo site_url(); ?>images/logo/enlace3.png" alt="Enlace" id="enlace3" class=" img-responsive" >
                    <div class="caption">
                      <p><a ng-click="cambiar('enlace3')" class="btn btn-primary" role="button">Cambiar</a></p>
                    </div>
                  </div>
                </div>

            </div>
            </div>
          </div>
                <div class="col-md-4" id="subida">
                  <form name="upload" ng-submit="uploadFile()">                
                      <div class="form-group" id="img" hidden>
                        <input  type="file"   class="form-control" placeholder="Imagen *"  id="userfile" name="userfile" uploader-model="file">
                        <p class="help-block text-danger"></p>
                        <button id="nuevab" hidden class="btn btn-success">Guardar</button>
                      </div>
                   </form>   
                </div>
          <div id="mensaje" class="col-lg-12 alert alert-info" align="center" hidden>
              {{mensaje}}
          </div>
  </div>
   <style type="text/css">
      .thumbnail  img{
    width: 60%;
    height: 120px;
  }
   </style>     
<script type="text/javascript">
        var app=angular.module("setting",[]);    
        app.controller("setcontroller",function ($scope,$http,upload){    
            $scope.img="";$scope.imagen="";
          $scope.cambiar=function(img){
            $scope.tipoiamgen=img;
            if(img=='logooax'){
              $("#img").removeAttr("class","right");
              $("#img").attr("class","pull-left");
            }else{
              $("#img").removeAttr("class","left");
               $("#img").attr("class","pull-right");
            }
            if($scope.img==img||$scope.img.length==0){
              if($('#img').is(':visible')){
                $('#img').fadeOut(200);
              }else{
                $('#img').fadeIn(200);
              }
            }else{
              if($('#img').is(':visible')){
                $('#img').fadeIn(200);
              }else{
                $('#img').fadeOut(200);
              }
            }  
          $scope.img=img;
          }

          $scope.uploadFile=function(){
    alertify.log("Espere un momento...");
              $("#images").fadeOut(100);
              $("#subida").fadeOut(100);
              $("#carga").fadeIn(100);
              var file=$scope.file;var image=$scope.tipoiamgen;
              url="<?php echo base_url()?>home/logo/cambiar";
              upload.uploadFile(file,image,url).then(function(res){
        $("#carga").fadeOut(100);
              $("#subida").fadeIn(100);
              $("#images").fadeIn(100);
              console.log(res.data);
              $scope.mensaje=res.data.mensaje;
                  if(res.data.mensaje){
                           alertify.success(res.data.mensaje);
                            $('#img').fadeOut(200);
                            $('#'+image).fadeOut();
                             document.getElementById(image).setAttribute('src',"<?php echo base_url(); ?>images/logo/"+image+".png?timestamp="+new Date().getTime());
                            $('#'+image).fadeIn(300);
                    }
                            
          })
      }
        }).directive('uploaderModel',["$parse",function ($parse) {
    return {
        scope: 'A',
        link: function (scope,iElement, iAttrs) {
            iElement.on("change",function(e){
                $parse(iAttrs.uploaderModel).assign(scope,iElement[0].files[0]);
            });
        }
    };
}]).service('upload',["$http","$q",function ($http, $q){
            this.uploadFile=function(file,imagen,url){
                var deferred=$q.defer();
                var formData=new FormData();
                formData.append("file",file);
                formData.append("img",imagen);      
                return $http.post(url,formData,{
                    headers:{
                        "Content-type":undefined
                    },
                    transformRequest: formData
                }).success(function(res){
                    deferred.resolve(res);
                })
                .error(function(msg,code){
                    deferred.reject(msg);
                })
                return deferred.promise;
            }    
        }]);
;  
</script>
<?php } ?>