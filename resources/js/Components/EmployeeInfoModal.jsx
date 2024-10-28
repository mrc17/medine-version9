import Modal from './Modal';

export default function EmployeeInfoModal({ show, onClose, selectedEmployee }) {
    if (!selectedEmployee) return null;

    return (
        <Modal show={show} onClose={onClose}>
            <div className="p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800">
                <h3 className="text-lg font-medium text-gray-900 dark:text-white">Informations de l'Employé</h3>
                <div className="mt-4">
                    <p className="text-sm text-gray-700 dark:text-gray-300"><strong>Nom :</strong> {selectedEmployee.user.nom}</p>
                    <p className="text-sm text-gray-700 dark:text-gray-300"><strong>Prénom :</strong> {selectedEmployee.user.prenom}</p>
                    <p className="text-sm text-gray-700 dark:text-gray-300"><strong>Téléphone :</strong> {selectedEmployee.user.telephone}</p>
                    <p className="text-sm text-gray-700 dark:text-gray-300"><strong>Poste :</strong> {selectedEmployee.post}</p>
                    <p className="text-sm text-gray-700 dark:text-gray-300"><strong>Type :</strong> {selectedEmployee.user.role.nom}</p>
                    <p className="text-sm text-gray-700 dark:text-gray-300"><strong>Statut :</strong> {selectedEmployee.user.etat === 1 ? 'Actif' : 'Inactif'}</p>
                    {/* Ajoutez d'autres informations selon vos besoins */}
                </div>
                <div className="mt-4">
                    <button
                        onClick={onClose}
                        className="py-2 px-4 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                        Fermer
                    </button>
                </div>
            </div>
        </Modal>
    );
}
