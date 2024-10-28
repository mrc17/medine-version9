import React from 'react'

function Entreprises() {
    return (
        <section id="entreprises" className="bg-white primary">
            <div className="py-8 lg:py-16 mx-auto max-w-screen-xl px-4">
                <h2 className="mb-8 lg:mb-16 text-3xl font-extrabold tracking-tight leading-tight text-center text-gray-900 dark:text-white md:text-4xl">PARTENAIRES</h2>
                <div className="grid grid-cols-2 gap-2 text-gray-500 sm:gap-2 md:grid-cols-3 lg:grid-cols-6 dark:text-gray-400">
                    <a href="#" className="flex justify-center h-20 items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                            <style>
                                {`.large-text {
                                    font-family: Arial, sans-serif;
                                    font-size: 40px;
                                    fill: #6b7280; /* Dark color */
                                    font-weight: bold;
                                }
                                .large-text:hover {
                                    fill: #ffffff; /* Change fill on hover */
                                }`}
                            </style>
                            <text x="50%" y="50%" className="large-text" dominant-baseline="middle" text-anchor="middle">
                                SBTA
                            </text>
                        </svg>

                    </a>
                    <a href="#" className="flex justify-center h-20 items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                            <style>
                                {`.large-text {
                                    font-family: Arial, sans-serif;
                                    font-size: 48px;
                                    fill: #6b7280; /* Dark color */
                                    font-weight: bold;
                                }
                                .large-text:hover {
                                    fill: #ffffff; /* Change fill on hover */
                                }`}
                            </style>
                            <text x="50%" y="50%" className="large-text" dominant-baseline="middle" text-anchor="middle">
                                SDS
                            </text>
                        </svg>
                    </a>
                    <a href="#" className="flex justify-center h-20 items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                            <style>
                                {`.large-text {
                                    font-family: Arial, sans-serif;
                                    font-size: 48px;
                                    fill: #6b7280; /* Dark color */
                                    font-weight: bold;
                                }
                                .large-text:hover {
                                    fill: #ffffff; /* Change fill on hover */
                                }`}
                            </style>
                            <text x="50%" y="50%" className="large-text" dominant-baseline="middle" text-anchor="middle">
                                MT
                            </text>
                        </svg>
                    </a>
                    <a href="#" className="flex justify-center h-20 items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                            <style>
                                {`.large-text {
                                    font-family: Arial, sans-serif;
                                    font-size: 48px;
                                    fill: #6b7280; /* Dark color */
                                    font-weight: bold;
                                }
                                .large-text:hover {
                                    fill: #ffffff; /* Change fill on hover */
                                }`}
                            </style>
                            <text x="50%" y="50%" className="large-text" dominant-baseline="middle" text-anchor="middle">
                                CTE
                            </text>
                        </svg>
                    </a>
                    <a href="#" className="flex justify-center h-20 items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                            <style>
                                {`.large-text {
                                    font-family: Arial, sans-serif;
                                    font-size: 48px;
                                    fill: #6b7280; /* Dark color */
                                    font-weight: bold;
                                }
                                .large-text:hover {
                                    fill: #ffffff; /* Change fill on hover */
                                }`}
                            </style>
                            <text x="50%" y="50%" className="large-text" dominant-baseline="middle" text-anchor="middle">
                                WAVE
                            </text>
                        </svg>
                    </a>
                    <a href="#" className="flex justify-center h-20 items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                            <style>
                                {`.large-text {
                                    font-family: Arial, sans-serif;
                                    font-size: 40px;
                                    fill: #6b7280; /* Dark color */
                                    font-weight: bold;
                                }
                                .large-text:hover {
                                    fill: #ffffff; /* Change fill on hover */
                                }`}
                            </style>
                            <text x="50%" y="50%" className="large-text" dominant-baseline="middle" text-anchor="middle">
                                ART
                            </text>
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    );
}

export default Entreprises;
