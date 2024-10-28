import React from 'react'
import { Link } from '@inertiajs/react'

function Notifications({ isNotification, toggleNotiDropdown }) {
    return (
        <div className="relative">
            <button
                type="button"
                onClick={toggleNotiDropdown}
                aria-expanded={isNotification}
                aria-haspopup="true"
                className="p-2 mr-1 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
            >
                <span className="sr-only">View notifications</span>
                {/* Bell Icon */}
                <svg
                    aria-hidden="true"
                    className="w-6 h-6"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                </svg>
            </button>

            {/* Notification Dropdown */}
            {isNotification && (
                <div className="absolute right-0 top-full mt-2 z-50 w-72 sm:w-96 bg-white divide-y divide-gray-100 shadow-lg rounded-xl dark:divide-gray-600 dark:bg-gray-700">
                    <div className="py-2 px-4 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-600 dark:text-gray-300">
                        Notifications
                    </div>
                    <div>
                        <Link
                            href="#"
                            className="flex py-3 px-4 border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600"
                        >
                            <div className="flex-shrink-0 relative">
                                <img
                                    className="w-11 h-11 rounded-full"
                                    src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/bonnie-green.png"
                                    alt="Bonnie Green avatar"
                                />
                                <div className="absolute bottom-0 right-0 w-5 h-5 rounded-full border border-white bg-primary-700 dark:border-gray-700 flex items-center justify-center">
                                    <svg
                                        className="w-3 h-3 text-white"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            fillRule="evenodd"
                                            d="M4 3a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h1v2a1 1 0 0 0 1.707.707L9.414 13H15a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4Z"
                                            clipRule="evenodd"
                                        />
                                        <path
                                            fillRule="evenodd"
                                            d="M8.023 17.215c.033-.03.066-.062.098-.094L10.243 15H15a3 3 0 0 0 3-3V8h2a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-1v2a1 1 0 0 1-1.707.707L14.586 18H9a1 1 0 0 1-.977-.785Z"
                                            clipRule="evenodd"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <div className="pl-3 w-full">
                                <div className="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400">
                                    New message from{" "}
                                    <span className="font-semibold text-gray-900 dark:text-white">Bonnie Green</span>
                                    : "Hey, what's up? All set for the presentation?"
                                </div>
                                <div className="text-xs font-medium text-primary-600 dark:text-primary-500">
                                    a few moments ago
                                </div>
                            </div>
                        </Link>
                    </div>
                    <Link
                        href="#"
                        className="block py-2 text-md font-medium text-center text-gray-900 bg-gray-50 hover:bg-gray-100 dark:bg-gray-600 dark:text-white dark:hover:underline"
                    >
                        <div className="inline-flex items-center">
                            <svg
                                aria-hidden="true"
                                className="mr-2 w-4 h-4 text-gray-500 dark:text-gray-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path
                                    fillRule="evenodd"
                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                    clipRule="evenodd"
                                ></path>
                            </svg>
                            View all
                        </div>
                    </Link>
                </div>
            )}
        </div>
    )
}

export default Notifications
