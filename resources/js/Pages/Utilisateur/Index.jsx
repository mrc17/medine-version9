import { useState } from 'react';
import TitrePage from '@/Components/TitrePage';
import { Head, Link } from '@inertiajs/react';
import ModalUpdateUser from '@/Components/ModalUpdateUser';
import ModalDeleteUser from '@/Components/ModalDeleteUser';
import ModalResertUser from '@/Components/ModalResertUser';
import ModalUnlockUser from '@/Components/ModalUnlockUser'; // Ensure the path is correct
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import ModalAddUser from '@/Components/ModalAddUser';

export default function Index({ auth, utilisateurs }) {
    const [openDropdown, setOpenDropdown] = useState({});
    const [searchTerm, setSearchTerm] = useState('');
    const [currentPage, setCurrentPage] = useState(1);
    const itemsPerPage = 8;

    const filteredUtilisateurs = utilisateurs.filter(utilisateur =>
        utilisateur.nom.toLowerCase().includes(searchTerm.toLowerCase())
    );

    const totalPages = Math.ceil(filteredUtilisateurs.length / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const currentUtilisateurs = filteredUtilisateurs.slice(startIndex, startIndex + itemsPerPage);

    const toggleDropdown = (id) => {
        setOpenDropdown((prev) => ({
            ...Object.keys(prev).reduce((acc, key) => {
                if (key !== id.toString()) {
                    acc[key] = false;
                }
                return acc;
            }, {}),
            [id]: !prev[id],
        }));
    };

    const handleKeyDown = (e, id) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggleDropdown(id);
        }
    };

    const handlePageChange = (page) => {
        setCurrentPage(page);
    };

    const getStatusClass = (utilisateur) => {
        if (utilisateur.attempt_logins < 4 && utilisateur.etat === 1) {
            return 'bg-green-500';
        } else if (utilisateur.attempt_logins < 4 && utilisateur.etat === 0) {
            return 'bg-red-500';
        } else {
            return 'bg-yellow-500';
        }
    };

    const [modalAddUser,setModalAddUser] = useState(false);
    const [modalUpdateUser, setModalUpdateUser] = useState(false);
    const [modalDeleteUser, setModalDeleteUser] = useState(false);
    const [modalResertUser, setModalResertUser] = useState(false);
    const [modalUnlockUser, setModalUnlockUser] = useState(false);
    const [selectUser, setSelectUser] = useState(null);

    const onClose = () => {
        setSelectUser(null);
        setModalAddUser(false);
        setModalUpdateUser(false);
        setModalDeleteUser(false);
        setModalResertUser(false);
        setModalUnlockUser(false);
    };

    const DropdownMenu = ({ utilisateur }) => (
        <div className="absolute z-50 w-44 -bottom-15 right-4 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
            <ul className="py-1 text-sm text-gray-700 dark:text-gray-200">
                <li>
                    <Link href={route('utilisateur.show', { utilisateur })} className="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Afficher</Link>
                </li>
                <li>
                    <button onClick={() => { setModalUpdateUser(true); setSelectUser(utilisateur); }} className="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Modifier</button>
                </li>
                {utilisateur.etat === 0 && (
                    <li>
                        <button onClick={() => { setModalUnlockUser(true); setSelectUser(utilisateur); }} className="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Débloquer</button>
                    </li>
                )}
                <li>
                    <button onClick={() => { setModalResertUser(true); setSelectUser(utilisateur); }} className="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Réinitialiser</button>
                </li>
            </ul>
            <div className="py-1">
                <button onClick={() => { setModalDeleteUser(true); setSelectUser(utilisateur); }} className="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Supprimer</button>
            </div>
        </div>
    );

    return (
        <AuthenticatedLayout user={auth} header={<TitrePage page="Utilisateurs" />}>
            <Head title="Dashboard" />

            {/* Modal Components */}
            <ModalAddUser show={modalAddUser} onClose={onClose} />
            <ModalUpdateUser show={modalUpdateUser} utilisateur={selectUser} onClose={onClose} />
            <ModalDeleteUser show={modalDeleteUser} utilisateur={selectUser} onClose={onClose} />
            <ModalResertUser show={modalResertUser} utilisateur={selectUser} onClose={onClose} />
            <ModalUnlockUser show={modalUnlockUser} utilisateur={selectUser} onClose={onClose} />

            <div className="py-5">
                <div className="w-full mx-auto sm:px-6 lg:px-8">
                    <section className="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
                        <div className="mx-auto max-w-screen-xl px-4 lg:px-12">
                            <div className="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                                <div className="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                                    <div className="w-full md:w-1/2">
                                        <form className="flex items-center">
                                            <label htmlFor="simple-search" className="sr-only">Search</label>
                                            <div className="relative w-full">
                                                <div className="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg aria-hidden="true" className="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fillRule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clipRule="evenodd" />
                                                    </svg>
                                                </div>
                                                <input
                                                    type="text"
                                                    id="simple-search"
                                                    className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                    placeholder="Search"
                                                    value={searchTerm}
                                                    onChange={(e) => setSearchTerm(e.target.value)}
                                                    required
                                                />
                                            </div>
                                        </form>
                                    </div>
                                    <div className="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                       {auth.role_id==4 &&
                                        <button onClick={()=>{setModalAddUser(true)}} type="button" className="flex items-center justify-center bg-green-500 text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                            <svg className="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path clipRule="evenodd" fillRule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                            </svg>
                                            Ajouter un Utilisateur
                                        </button>
                                       }
                                    </div>
                                </div>
                                <div className="overflow-x-auto">
                                    <table className="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" className="px-4 py-3">Nom</th>
                                                <th scope="col" className="px-4 py-3">Prénom</th>
                                                <th scope="col" className="px-4 py-3">Téléphone</th>
                                                <th scope="col" className="px-4 py-3">Rôle</th>
                                                <th scope="col" className="px-4 py-3">Status du compte</th>
                                                <th scope="col" className="px-4 py-3">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {currentUtilisateurs.map((utilisateur) => (
                                                <tr key={utilisateur.id} className="border-b dark:border-gray-700">
                                                    <th scope="row" className="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        {utilisateur.nom}
                                                    </th>
                                                    <td className="px-4 py-3">{utilisateur.prenom}</td>
                                                    <td className="px-4 py-3">{utilisateur.telephone}</td>
                                                    <td className="px-4 py-3">{utilisateur.role.nom}</td>
                                                    <td className="px-4 items-center justify-center py-4">
                                                        <div className={`w-5 h-5 rounded-full ${getStatusClass(utilisateur)}`}></div>
                                                    </td>
                                                    <td className="px-4 py-3 flex items-center justify-center">
                                                        <button
                                                            onClick={() => toggleDropdown(utilisateur.id)}
                                                            onKeyDown={(e) => handleKeyDown(e, utilisateur.id)}
                                                            className="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                                            type="button"
                                                            tabIndex={0} // Makes it focusable
                                                        >
                                                            <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fillRule="evenodd" d="M5 8a1 1 0 011 1v2a1 1 0 11-2 0V9a1 1 0 011-1zm5 0a1 1 0 011 1v2a1 1 0 11-2 0V9a1 1 0 011-1zm5 0a1 1 0 011 1v2a1 1 0 11-2 0V9a1 1 0 011-1z" clipRule="evenodd" />
                                                            </svg>
                                                        </button>
                                                        {openDropdown[utilisateur.id] && <DropdownMenu utilisateur={utilisateur} />}
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                                <div className="flex items-center justify-between p-4">
                                    <button onClick={() => handlePageChange(Math.max(currentPage - 1, 1))} disabled={currentPage === 1} className="p-2 text-gray-500 hover:text-gray-700 disabled:opacity-50">Previous</button>
                                    <span>Page {currentPage} of {totalPages}</span>
                                    <button onClick={() => handlePageChange(Math.min(currentPage + 1, totalPages))} disabled={currentPage === totalPages} className="p-2 text-gray-500 hover:text-gray-700 disabled:opacity-50">Next</button>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
