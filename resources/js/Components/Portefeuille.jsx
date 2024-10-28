import React from 'react';

const Portefeuille = ({ title, value }) => (
    <div className="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
        <h2 className="text-2xl font-semibold text-gray-800 dark:text-white">{title}</h2>
        <div className="font-bold text-center text-xl sm:text-xl lg:text-2xl mt-4">
            {value !== undefined ? `${value} Franc CFA` : 'Chargement...'}  {/* Spinner if data is not loaded */}
        </div>
    </div>
);

export default Portefeuille;
