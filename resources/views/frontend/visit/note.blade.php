@extends('frontend.app')

@section('includes.js')
    @parent

    <script src="/plugins/moment/moment.min.js" type="text/javascript"></script>
    <script src="/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>

    <script type="text/javascript">
    </script>
    @stop

@section('header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Social
            <small>Lista de visitas de toda la empresa.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">
                <i class="fa fa-comments-o"></i> Social
            </li>
        </ol>
    </section>
@stop

@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-md-12">
            <!-- The time line -->
            <ul class="timeline">

                @foreach ($datos as $key => $visit)

                    @if ($key == 0)
                <!-- timeline time label -->
                <li class="time-label">
                  <span class="bg-red">
                    {{ Carbon::createFromFormat('Y-m-d H:i:s',$visit->start)->toFormattedDateString() }}
                  </span>
                </li>
                    @else
                        @if (Carbon::createFromFormat('Y-m-d H:i:s',$visit->start)->diffInDays(Carbon::createFromFormat('Y-m-d H:i:s',$datos[$key-1]->start)) > 1)
                            <li class="time-label">
                                <span class="bg-red">
                                    {{ Carbon::createFromFormat('Y-m-d H:i:s',$visit->start)->toFormattedDateString() }}
                                </span>
                            </li>
                            @endif
                    @endif

                <!-- /.timeline-label -->
                <!-- timeline item -->
                <li>
                    <i class="fa fa-user-md bg-aqua"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> {{ Carbon::createFromFormat('Y-m-d H:i:s',$visit->start)->diffForHumans() }}</span>
                        <h3 class="timeline-header no-border"><a href="#">{{ $visit->user->closeup_name }}</a> visitó al doctór {{ $visit->client->closeup_name }}</h3>
                    </div>
                </li>
                <!-- END timeline item -->

                @endforeach


                <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                </li>
            </ul>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
