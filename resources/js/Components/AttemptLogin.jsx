import React from 'react';
import PrimaryButton from './PrimaryButton';

const AttemptLogin = ({ title, value }) => (
    <div className="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
        <h2 className="text-2xl font-semibold text-gray-800 dark:text-white">{title}</h2>
        <div className="font-bold text-center text-xl sm:text-xl lg:text-2xl mt-4">
            {value}
        </div>
        <div className="w-full flex items-end justify-end mt-4">
            <PrimaryButton className='bg-green-700'>
                Réinitialiser
            </PrimaryButton>
        </div>
    </div>
);

export default AttemptLogin;
