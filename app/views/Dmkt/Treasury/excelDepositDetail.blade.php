<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Solicitud</th>
                    <th>N. Operacion</th>
                    <th>Observacion</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $depositos as $idSolicitud => $deposit )
                    @if( $deposit[ status ] === ok )
                        <tr>
                            <td style="background-color:#00dd0d"></td>
                            <td>{{ $idSolicitud }}</td>
                            <td>{{{ $deposit[ 'operacion' ] or '' }}}</td>
                            <td>Ok</td>
                        </tr>    
                    @elseif( $deposit[ status ] === warning )
                        <tr>
                            <td  style="background-color:#f5f904"></td>
                            <td>{{ $idSolicitud }}</td>
                            <td>{{ $deposit{ 'operacion' } }}</td>
                            <td>{{ $deposit[ description ] }}</td>
                        </tr>       
                    @else
                        <tr>
                            <td style="background-color:#f90404"></td>
                            <td>{{ $idSolicitud }}</td>
                            <td>{{ $deposit[ 'operacion' ] }}</td>
                            <td>{{ $deposit[ description ] }}</td>
                        </tr>       
                    @endif
                @endforeach
            </tbody>
        </table>
    </body>
</html>