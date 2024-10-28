import { useEffect } from 'react';
import Checkbox from '@/Components/Checkbox';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Head, Link, useForm } from '@inertiajs/react';
import LabelForm from '@/Components/LabelForm';
import ApplicationLogo from '@/Components/ApplicationLogo';
import "../../../css/style.css"

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        login: '',
        password: '',
        remember: false,
    });

    useEffect(() => {
        return () => {
            reset('password');
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();

        post(route('login'));
    };

    return (
        <GuestLayout>
            <Head title="Connexion" />
            <LabelForm className="text-white" label="Connexion" />
            {
                errors.message && <>
                    <div className="bg-red-300 border-red-400 border-2 items-center mt-2 rounded-md justify-center flex p-2 w-full">
                        <InputError className='font-bold' message={errors.message} />
                    </div>
                </>
            }
            <div className='w-full justify-center items-center'>
                {status && <div className="mb-4 font-medium text-sm text-green-600">{status}</div>}
                <form onSubmit={submit}>

                    <div className="mt-4">
                        <InputLabel htmlFor="login" value="Nom d'utilisateur" />

                        <TextInput
                            id="login"
                            type="tel"
                            name="login"
                            value={data.login}
                            className="mt-1 block w-full"
                            autoComplete="tel"
                            onChange={(e) => setData('login', e.target.value)}
                            required
                        />

                        <InputError message={errors.login} className="mt-2" />
                    </div>

                    <div className="mt-4">
                        <InputLabel htmlFor="password" value="Mot de passe" />

                        <TextInput
                            id="password"
                            type="password"
                            name="password"
                            value={data.password}
                            className="mt-1 block w-full"
                            autoComplete="current-password"
                            onChange={(e) => setData('password', e.target.value)}
                            required
                        />

                        <InputError message={errors.password} className="mt-2" />
                    </div>

                    <div className="block mt-4">
                        <label className="flex items-center">
                            <Checkbox
                                name="remember"
                                checked={data.remember}
                                onChange={(e) => setData('remember', e.target.checked)}
                            />
                            <span className="ms-2 text-color text-sm">Souviens-toi de moi</span>
                        </label>
                    </div>
                    <div className="flex items-center justify-end mt-4">
                        <PrimaryButton className="ms-4 secondary" disabled={processing}>
                            Connexion
                        </PrimaryButton>
                    </div>
                </form>
            </div>

        </GuestLayout>
    );
}
