@extends('admin.layout')

@section('admin-title') Species Approval Checklists @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Species' => 'admin/data/species-approval-checklists']) !!}

<h1>Species Approval Checklists</h1>

@if(!count($specieses))
    <p>No species found.</p>
@else 
    <table class="table table-sm species-table">
    <thead>
            <tr>
                <th>Species</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($specieses as $species)
                <tr>
                    <td>
                        {!! $species->displayName !!}
                    </td>
                    <td class="text-right">
                        <a href="{{ url('admin/data/species-approval-checklists/edit/'.$species->id) }}" class="btn btn-primary">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

@endif

@endsection
