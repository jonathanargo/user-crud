import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useForm, Head } from '@inertiajs/react';
import Button from 'react-bootstrap/Button';
import { Alert, Col, Row, Table, Dropdown } from 'react-bootstrap';
import { router } from '@inertiajs/react';

import {
    DatatableWrapper,
    Filter,
    Pagination,
    PaginationOptions,
    TableBody,
    TableHeader
} from 'react-bs-datatable';

export default function Index({ auth, users }) {
    const header = [
        { title: 'First Name', prop: 'first_name', isSortable: true},
        { title: 'Last Name', prop: 'last_name', isSortable: true},
        { title: 'Email', prop: 'email', isSortable: true},
        { title: '', prop: 'actions', cell: (user) => (
            <Dropdown>
                <Dropdown.Toggle variant="light" id="dropdown-basic">
                    Actions
                </Dropdown.Toggle>
                <Dropdown.Menu>
                    <Dropdown.Item onClick={() => router.visit(route('users.show', user.id))}>Details</Dropdown.Item>
                    <Dropdown.Item onClick={() => handleDeleteUser(user.id)}>Delete</Dropdown.Item>
                </Dropdown.Menu>
                {/*<Button variant="danger" onClick={() => handleDeleteUser(user.id)}>Delete</Button>*/}
            </Dropdown>
        )}
    ];

    const handleDeleteUser = (id) => {
        console.log("Deleting user "+id);
    }

    const showCreateForm = () => {
        router.visit(route('users.create'));
    }

    function TableComponent({ users }) {
        return (
            <DatatableWrapper 
                body={users}
                headers={header}
                paginationOptionsProps={{
                    initialState: {
                      rowsPerPage: 10,
                      options: [5, 10, 15, 20]
                    }
                }}
            >
                <Row className="mb-4">
                    <Col xs={12} lg={4} className="d-flex flex-col justify-content-end align-items-start">
                        <Button variant="primary" onClick={showCreateForm}>Create User</Button>
                    </Col>
                    
                    <Col xs={12} sm={6} lg={4} className="d-flex flex-col justify-content-end align-items-end">
                        <Pagination />
                    </Col>
                    <Col xs={12} sm={6} lg={4} className="d-flex flex-col justify-content-lg-center align-items-center justify-content-sm-start mb-2 mb-sm-0">
                        <PaginationOptions />
                    </Col>
                </Row>
                <Table>
                    <TableHeader />
                    <TableBody />
                </Table>
            </DatatableWrapper>
        );
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Users" />
            <div className="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
                <TableComponent users={users} />
            </div>
        </AuthenticatedLayout>
    );
}