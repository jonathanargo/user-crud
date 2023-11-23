
import React, { useState, useEffect } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Form, Button } from 'react-bootstrap';
import { useForm, Head, router } from '@inertiajs/react';
import InputError from '@/Components/InputError';
import { Alert } from 'react-bootstrap';
import { getCodeList } from 'country-list';
import states from 'states-us';

import InputMask from 'react-input-mask';

export default function UserForm({ auth, user, mode = 'create'}) {

    const { data, setData, post, patch, processing, errors, clearErrors } = useForm({
        id: '',
        first_name: '',
        last_name: '',
        email: '',
        mobile_number: '',
        address: '',
        city: '',
        state: '',
        zip: '',
        country: ''
    });

    useEffect(() => {
        if (mode === 'edit') {
            setData(user);
        }
    }, [mode, user]);

    const [showError, setShowError] = useState(false);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setData({ ...data, [name]: value });
    };

    const submitCallback = (e) => {
        e.preventDefault;
        if (mode === 'create') {
            post(route('users.store'), {
                onError: (e) => {
                    console.error(e);
                    setShowError(true);
                }
            });
        } else {
            patch(route('users.update', user.id), {
                onError: (e) => {
                    console.error(e);
                    setShowError(true);
                }
            });
        }
    };

    const closeCallback = () => {
        router.visit(route('users.index'));
    };

    let title = (mode === 'create' ? 'Create User' : 'Update User');

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title={title} />
            <div className="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
                <h1 className="font-bold">Profile Info</h1>
                <p>Manage your personal information, timezone, and profile image.</p>
                {
                    showError &&
                    <Alert variant="danger" onClose={() => setShowError(false)} dismissible>
                        {Object.values(errors).flat().map((error, index) => (
                            <p key={index}>{error}</p>
                        ))}
                    </Alert>
                }
                <Form>
                    <Form.Group controlId="studentId">
                        <Form.Control type="hidden" name="id" value={data.id} />
                    </Form.Group>
                    <div className="flex space-x-4">
                        
                        <Form.Group controlId="first_name" className="mb-3 flex-grow">
                            <Form.Label>First Name</Form.Label>
                            <Form.Control
                                type="text"
                                name="first_name"
                                value={data.first_name}
                                onChange={handleChange}
                            />
                            <InputError message={errors['first_name']} className="mt-2" />
                        </Form.Group>

                        <Form.Group controlId="last_name" className="mb-3 flex-grow">
                            <Form.Label>Last Name</Form.Label>
                            <Form.Control
                                type="text"
                                name="last_name"
                                value={data.last_name}
                                onChange={handleChange}
                            />
                            <InputError message={errors['last_name']} className="mt-2" />
                        </Form.Group>
                    </div>

                    <Form.Group controlId="email" className="mb-3">
                        <Form.Label>Email</Form.Label>
                        <Form.Control
                            type="email"
                            name="email"
                            value={data.email}
                            onChange={handleChange}
                        />
                        <InputError message={errors['email']} className="mt-2" />
                    </Form.Group>

                    <Form.Group controlId="mobile_number" className="mb-3">
                        <Form.Label>Mobile Number</Form.Label>
                        <InputMask
                            mask="999-999-9999"
                            maskChar="_"
                            value={data.mobile_number}
                            onChange={handleChange}
                        >
                        {() => <Form.Control type="text" name="mobile_number" />}
                        </InputMask>
                        <InputError message={errors['mobile_number']} className="mt-2" />
                    </Form.Group>

                    <Form.Group controlId="address" className="mb-3">
                        <Form.Label>Address</Form.Label>
                        <Form.Control
                            type="text"
                            name="address"
                            value={data.address}
                            onChange={handleChange}
                        />
                        <InputError message={errors['address']} className="mt-2" />
                    </Form.Group>

                    <Form.Group controlId="city" className="mb-3">
                        <Form.Label>City</Form.Label>
                        <Form.Control
                            type="text"
                            name="city"
                            value={data.city}
                            onChange={handleChange}
                        />
                        <InputError message={errors['city']} className="mt-2" />
                    </Form.Group>

                    <Form.Group controlId="state" className="mb-3">
                        <Form.Label>State / Province</Form.Label>
                        <Form.Select name="state" value={data.state} onChange={handleChange}>
                            <option key="" value=""></option>
                            {states.map((state, index) => (
                                <option key={index} value={state.abbreviation}>{state.name}</option>
                            ))}
                        </Form.Select>
                        <InputError message={errors['state']} className="mt-2" />
                    </Form.Group>

                    <Form.Group controlId="zip" className="mb-3">
                        <Form.Label>Postal Code</Form.Label>
                        <Form.Control
                            type="text"
                            name="zip"
                            value={data.zip}
                            onChange={handleChange}
                        />
                        <InputError message={errors['zip']} className="mt-2" />
                    </Form.Group>

                    <Form.Group controlId="country" className="mb-3">
                        <Form.Label>Country</Form.Label>
                        <Form.Select name="country" value={data.country} onChange={handleChange}>
                            <option key="" value=""></option>
                            {Object.entries(getCodeList()).map(([code, name], index) => (
                                <option key={index} value={code}>
                                    {name}
                                </option>
                            ))}
                        </Form.Select>
                        <InputError message={errors['country']} className="mt-2" />
                    </Form.Group>
                    <hr />

                    <Button variant="primary" onClick={submitCallback} disabled={processing}>
                        Save
                    </Button>
                    <Button variant="secondary" onClick={closeCallback} disabled={processing} className="ml-3">Cancel</Button>
                </Form>
            </div>
        </AuthenticatedLayout>
    );
};
