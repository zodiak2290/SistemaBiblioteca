<div class="table-responsive">
    <table id="talba" class="table table-bordered table-hover table-striped">
        <thead>
           <tr>         
            <th >
            </th>
            <th ng-click="ordenar('titulo')">Título</th>
            <th ng-click="ordenar('clasificacion')">Clasificación</th>
            <th ng-click="ordenar('nameautor')">ISBN</th>
            <th colspan="3">¿Reservar?</th>
          </tr>
        </thead>
        <tbody ng-repeat="n in list_data| orderBy:campo:orden">
          <tr >
            <td><a  data-toggle="modal" href="#myModal" type="button" ng-click="buscaridficha(n.nadqu,'centro',$index)" ><span class="icon-eye"></span></a></td>                                                            
            <td class="titulo">{{n.titulo|capitalize}}</td>
            <td class="casificacion">{{n.clasificacion}}</td>
            <td class="nameautor">{{n.nameautor|capitalize}}</td>
            <td ng-click="reservar(n.nadqu)"><span class="icon-clipboard" style="cursor: pointer;" /></td>    
          </tr>
        </tbody>
        <tbody ng-if="list_data.length<=0">
         <tr>
            <td colspan="8" align="center">
              No hay resultados
          </td>
         </tr> 
        </tbody>
    </table>
</div>
<!-- /.table-responsive -->