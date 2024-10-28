import '../../../../css/style.css';
import TextInput from '@/Components/TextInput';
import { Transition } from '@headlessui/react';
import InputLabel from '@/Components/InputLabel';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import { useForm, usePage } from '@inertiajs/react';

export default function UpdateProfileInformation({ className = '' }) {
    const user = usePage().props.auth;

    const { data, setData, patch, errors, processing, recentlySuccessful } = useForm({
        nom: user.nom,
        telephone: user.telephone,
        path: user.info_employe.path,
        login: user.info_employe.login,
        adresse: user.info_employe.adresse,
    });

    const submit = (e) => {
        e.preventDefault();
        patch(route('profile.update'));
    };

    // Function to handle file changes
    const handleFileChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            const allowedTypes = ['image/svg+xml', 'image/png', 'image/jpeg', 'image/gif'];
            if (allowedTypes.includes(file.type)) {
                setData('path', file);
            } else {
                alert('Veuillez sélectionner un fichier de type SVG, PNG, JPG ou GIF.');
            }
        }
    };

    return (
        <section className={`bg-gray-800 p-6 rounded-lg ${className}`}>
            <header className="mb-4">
                <h2 className="text-2xl font-semibold text-white">Informations du profil</h2>
                <p className="mt-1 text-sm text-gray-300">
                    Mettez à jour les informations de votre compte et votre numéro de téléphone.
                </p>
            </header>

            <form onSubmit={submit} className="space-y-6">
                <div className='grid grid-cols-1 md:grid-cols-2 gap-6'>
                    <div>
                        <InputLabel className='text-white' htmlFor="nom" value="Nom" />
                        <TextInput
                            id="nom"
                            className="mt-1 block w-full"
                            value={data.nom}
                            onChange={(e) => setData('nom', e.target.value)}
                            required
                            isFocused
                            autoComplete="nom"
                        />
                        <InputError className="mt-2" message={errors.nom} />
                    </div>

                    <div>
                        <InputLabel htmlFor="telephone" value="Téléphone" />
                        <TextInput
                            id="telephone"
                            type="tel"
                            className="mt-1 block w-full"
                            value={data.telephone}
                            onChange={(e) => setData('telephone', e.target.value)}
                            required
                            autoComplete="tel"
                        />
                        <InputError className="mt-2" message={errors.telephone} />
                    </div>

                    <div>
                        <InputLabel htmlFor="login" value="Login" />
                        <TextInput
                            id="login"
                            type="text"
                            className="mt-1 block w-full"
                            value={data.login}
                            onChange={(e) => setData('login', e.target.value)}
                            required
                            autoComplete="login"
                        />
                        <InputError className="mt-2" message={errors.login} />
                    </div>

                    <div>
                        <InputLabel htmlFor="adresse" value="Adresse" />
                        <TextInput
                            id="adresse"
                            type="text"
                            className="mt-1 block w-full"
                            value={data.adresse}
                            onChange={(e) => setData('adresse', e.target.value)}
                            required
                            autoComplete="adresse"
                        />
                        <InputError className="mt-2" message={errors.adresse} />
                    </div>

                    {user.info_employe.path === null && (
                        <div className="col-span-1 md:col-span-2 flex flex-col items-center">
                            <label htmlFor="dropzone-file" className="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-800 hover:bg-gray-900">
                                <div className="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg className="w-10 h-10 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                    </svg>
                                    <p className="mb-2 text-sm text-gray-500">
                                        <span className="font-semibold">Cliquez pour télécharger</span> ou faites glisser et déposez
                                    </p>
                                    <p className="text-xs text-gray-500">SVG, PNG, JPG ou GIF (MAX. 800x400px)</p>
                                </div>
                                <input
                                    id="dropzone-file"
                                    type="file"
                                    name='path'
                                    className="hidden"
                                    onChange={handleFileChange}
                                />
                            </label>
                        </div>
                    )}
                </div>

                <div className="flex items-center gap-4 mt-4">
                    <PrimaryButton disabled={processing}>
                        {processing ? 'Enregistrement...' : 'Enregistrer'}
                    </PrimaryButton>
                    <Transition
                        show={recentlySuccessful}
                        enter="transition ease-in-out"
                        enterFrom="opacity-0"
                        leave="transition ease-in-out"
                        leaveTo="opacity-0"
                    >
                        <p className="text-sm text-gray-300">Enregistré.</p>
                    </Transition>
                </div>
            </form>
        </section>
    );
}
