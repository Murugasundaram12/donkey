@extends('layouts.app')

@section('title', 'Companies')

@section('content')
<div class="main-content-container container-fluid px-4">
    <div class="page-header">
        <div>
            <h1 class="page-title">Companies</h1>
            <div class="page-subtitle">
                <span id="total-companies">{{ $companies->total() }}</span> companies found
            </div>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
                <i class="fe fe-plus"></i> Add Company
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Company Code</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($companies as $company)
                                <tr>
                                    <td><strong>{{ $company->company_code }}</strong></td>
                                    <td>{{ $company->name }}</td>
                                    <td>{{ $company->email }}</td>
                                    <td>{{ $company->phone }}</td>
                                    <td>{{ $company->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-info btn-sm">
                                                <i class="fe fe-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-warning btn-sm">
                                                <i class="fe fe-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fe fe-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fe fe-building fe-48 text-muted mb-3"></i>
                                            <h5 class="mb-2">No companies found</h5>
                                            <p class="text-muted mb-4">Get started by creating a new company.</p>
                                            <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
                                                Add Company
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $companies->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

