import { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import TitrePage from '@/Components/TitrePage';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DeleteModalEmploye from '@/Components/DeleteModalEmploye';
import ResetPasswordModal from '@/Components/ResetPasswordModal';
import UnlockEmployeeModal from '@/Components/UnlockEmployeeModal';
import ModifyEmployeeModal from '@/Components/ModifyEmployeeModal';
import EmployeeInfoModal from '@/Components/EmployeeInfoModal';
import CreateEmployeeModal from '@/Components/CreateEmployeeModal';

export default function Index({ auth, employes, users }) {
    const [openDropdown, setOpenDropdown] = useState({});
    const [searchTerm, setSearchTerm] = useState('');
    const [currentPage, setCurrentPage] = useState(1);
    const itemsPerPage = 8;

    const [isOpenModal, setIsOpenModal] = useState(false);
    const [isOpenResetModal, setIsOpenResetModal] = useState(false);
    const [isOpenUnlockModal, setIsOpenUnlockModal] = useState(false);
    const [isOpenModifyModal, setIsOpenModifyModal] = useState(false);
    const [isOpenInfoModal, setIsOpenInfoModal] = useState(false);
    const [isOpenCreateModal, setIsOpenCreateModal] = useState(false);
    const [selectedEmployee, setSelectedEmployee] = useState(null);

    // Filter employees based on search term
    const filteredAffiliations = employes.filter((employe) =>
        employe.user.nom.toLowerCase().includes(searchTerm.toLowerCase()) ||
        employe.user.prenom.toLowerCase().includes(searchTerm.toLowerCase())
    );

    // Pagination logic
    const totalPages = Math.ceil(filteredAffiliations.length / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const currentAffiliations = filteredAffiliations.slice(startIndex, startIndex + itemsPerPage);

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

    const getStatusClass = (employe) => {
        if (employe.user.attempt_logins < 4 && employe.user.etat === 1) {
            return 'bg-green-500';
        } else if (employe.user.attempt_logins < 4 && employe.user.etat === 0) {
            return 'bg-red-500';
        } else {
            return 'bg-yellow-500';
        }
    };

    const handleCreateEmployee = (newEmployee) => {
        console.log('Nouvel Employé créé:', newEmployee);
    };

    const DropdownMenu = ({ employe }) => (
        <div className="absolute right-5 z-50 w-28 bg-white rounded shadow dark:bg-gray-700">
            <ul className="py-1 w-full text-sm text-gray-700 dark:text-gray-200">
                <li>
                    <button
                        onClick={() => {
                            setSelectedEmployee(employe);
                            setIsOpenInfoModal(true);
                        }}
                        className="block w-full text-left py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600">Infos</button>
                </li>
                <li>
                    <button
                        onClick={() => {
                            setSelectedEmployee(employe);
                            setIsOpenModifyModal(true);
                        }}
                        className="block py-2 px-4 w-full text-left hover:bg-gray-100 dark:hover:bg-gray-600">Modifier</button>
                </li>
                {employe.user.etat === 0 && (
                    <li>
                        <button
                            onClick={() => {
                                setSelectedEmployee(employe);
                                setIsOpenUnlockModal(true);
                            }}
                            className="block py-2 px-4 w-full text-left hover:bg-gray-100 dark:hover:bg-gray-600">Débloquer</button>
                    </li>
                )}
                <li>
                    <button
                        onClick={() => {
                            setSelectedEmployee(employe);
                            setIsOpenResetModal(true);
                        }}
                        className="block py-2 px-4 w-full text-left hover:bg-gray-100 dark:hover:bg-gray-600">Réinitialiser</button>
                </li>
                <li>
                    <button
                        onClick={() => {
                            setSelectedEmployee(employe);
                            setIsOpenModal(true);
                        }}
                        className="block py-2 px-4 w-full text-left hover:bg-gray-100 dark:hover:bg-gray-600">Supprimer</button>
                </li>
            </ul>
        </div>
    );

    const onClose = () => setIsOpenModal(false);
    const onCloseResetModal = () => setIsOpenResetModal(false);
    const onCloseUnlockModal = () => setIsOpenUnlockModal(false);
    const onCloseModifyModal = () => {
        setIsOpenModifyModal(false);
        setSelectedEmployee(null);
    };
    const onCloseInfoModal = () => {
        setIsOpenInfoModal(false);
        setSelectedEmployee(null);
    };

    return (
        <AuthenticatedLayout user={auth} header={<TitrePage page="Employés Médine" />}>
            <Head title="Liste des Employés" />
            <DeleteModalEmploye show={isOpenModal} onClose={onClose} selectedEmployee={selectedEmployee} />
            <EmployeeInfoModal show={isOpenInfoModal} onClose={onCloseInfoModal} selectedEmployee={selectedEmployee} />
            <ResetPasswordModal show={isOpenResetModal} onClose={onCloseResetModal} selectedEmployee={selectedEmployee} />
            <UnlockEmployeeModal show={isOpenUnlockModal} onClose={onCloseUnlockModal} selectedEmployee={selectedEmployee} />
            <ModifyEmployeeModal show={isOpenModifyModal} onClose={onCloseModifyModal} selectedEmployee={selectedEmployee} />
            <CreateEmployeeModal show={isOpenCreateModal} onClose={() => setIsOpenCreateModal(false)} users={users} onCreate={handleCreateEmployee}
            />
            <div className="py-5">
                <div className="w-full mx-auto sm:px-6 lg:px-8">
                    <section className="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
                        <div className="max-w-screen-xl mx-auto px-4 lg:px-12">
                            <div className="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                                <div className="flex flex-col md:flex-row items-center justify-between p-4">
                                    <div className="w-full md:w-1/2">
                                        <form className="flex items-center">
                                            <div className="relative w-full">
                                                <input
                                                    type="text"
                                                    id="simple-search"
                                                    className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg pl-10 p-2 w-full focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                                    placeholder="Rechercher"
                                                    value={searchTerm}
                                                    onChange={(e) => setSearchTerm(e.target.value)}
                                                />
                                            </div>
                                        </form>
                                    </div>
                                    <div className="w-full md:w-auto flex items-center justify-end">
                                        <button
                                            onClick={() => setIsOpenCreateModal(true)}
                                            className="bg-green-700 text-white font-medium rounded-lg text-sm px-4 py-2 hover:bg-green-800 focus:ring-4 dark:bg-green-600 dark:hover:bg-green-700">
                                            Ajouter un Employé
                                        </button>
                                    </div>
                                </div>
                                <div className="overflow-x-auto pb-24">
                                    <table className="w-full text-sm text-gray-500 dark:text-gray-400">
                                        <thead className="text-xs bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" className="px-4 py-3">Nom</th>
                                                <th scope="col" className="px-4 py-3">Prénom</th>
                                                <th scope="col" className="px-4 py-3">Téléphone</th>
                                                <th scope="col" className="px-4 py-3">Post</th>
                                                <th scope="col" className="px-4 py-3">Type</th>
                                                <th scope="col" className="px-4 py-3">Login</th>
                                                <th scope="col" className="px-4 py-3">Statut</th>
                                                <th scope="col" className="px-4 py-3">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {currentAffiliations.map((employe) => (
                                                <tr key={employe.id} className="border-b dark:border-gray-700">
                                                    <td className="px-4 py-3 font-medium text-gray-900 dark:text-white">{employe.user.nom}</td>
                                                    <td className="px-4 py-3">{employe.user.prenom}</td>
                                                    <td className="px-4 py-3">{employe.user.telephone}</td>
                                                    <td className="px-4 py-3">{employe.post}</td>
                                                    <td className="px-4 py-3">{employe.user.role.nom}</td>
                                                    <td className="px-4 py-3">{employe.login}</td>
                                                    <td className="px-4 py-3">
                                                        <div className={`w-5 h-5 rounded-full ${getStatusClass(employe)}`}></div>
                                                    </td>
                                                    <td className="px-4 py-3">
                                                        <button
                                                            className="p-2 text-gray-900 bg-white rounded-lg dark:text-white dark:bg-gray-800"
                                                            onClick={() => toggleDropdown(employe.id)}
                                                            onKeyDown={(e) => handleKeyDown(e, employe.id)}
                                                        >
                                                            <svg className="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                <path d="M5.293 9.707a1 1 0 011.414 0L10 13.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                                            </svg>
                                                        </button>
                                                        {openDropdown[employe.id] && <DropdownMenu employe={employe} />}
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                                <div className="flex justify-between items-center px-4 py-3">
                                    <span className="text-sm text-gray-500">Page {currentPage} sur {totalPages}</span>
                                    <div className="space-x-2">
                                        {Array.from({ length: totalPages }, (_, index) => index + 1).map((page) => (
                                            <button
                                                key={page}
                                                onClick={() => handlePageChange(page)}
                                                className={`px-3 py-1 rounded-md ${currentPage === page ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-900'}`}>
                                                {page}
                                            </button>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
