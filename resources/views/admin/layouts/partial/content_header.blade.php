<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{$page_title}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @foreach($breadcrumb as $list)
                        <li class="breadcrumb-item {{$list['active'] ? 'active' : ''}}">
                            @if($list['url'])
                                <a href="{{$list['url']}}">{{$list['title']}}</a>
                            @else
                                {{$list['title']}}
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>

<!-- Content Header (Page header) -->
