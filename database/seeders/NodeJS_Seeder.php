<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\NodeJS\Project;
use App\Models\NodeJS\ProjectsDefaultFileStructure;
use App\Models\NodeJS\ExecutionStep;
use App\Models\NodeJS\ProjectExecutionStep;

class NodeJS_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // user
        User::create([
            'name' => 'Omar',
            'email' => 'omar.yem1111@gmail.com',
            'password' => Hash::make('123456789'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // projects
        Project::insert([[
            'title' => 'api-experiment',
            'description' => 'This is an API and web project using NodeJS, ExpressJS, and MongoDB. The goal of this project is to try testing API endpoints and Web pages using Jest, Supertest, and Puppeteer.',
            'tech_stack' => json_encode([
                'framework' => 'ExpressJS',
                'language' => 'NodeJS',
                'database' => 'MongoDB',
                'testing' => 'Jest, Supertest, Puppeteer',
            ]),
            'github_url' => 'https://github.com/Omar630603/api-experiment',
            'image' => 'image',
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'title' => 'auth-experiment',
            'description' => 'This is an API and web project using NodeJS, ExpressJS, and MongoDB. The goal of this project is to try testing API endpoints and Web pages using Jest, Supertest, and Puppeteer.',
            'tech_stack' => json_encode([
                'framework' => 'ExpressJS',
                'language' => 'NodeJS',
                'database' => 'MongoDB',
                'testing' => 'Jest, Supertest, Puppeteer',
            ]),
            'github_url' => 'https://github.com/Omar630603/auth-experiment',
            'image' => 'image',
            'created_at' => now(),
            'updated_at' => now(),
        ]]);


        $project_api_experiment = Project::where('title', 'api-experiment')->first();
        $project_auth_experiment = Project::where('title', 'auth-experiment')->first();

        // images
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/images/api-experiment.png'))->toMediaCollection('project_images', 'nodejs_public_projects_files');
//
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/images/auth-experiment.png'))->toMediaCollection('project_images', 'nodejs_public_projects_files');
//
//        // files
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/files/.env'))->toMediaCollection('project_files', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/files/package.json'))->toMediaCollection('project_files', 'nodejs_public_projects_files');
//
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/files/.env'))->toMediaCollection('project_files', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/files/package.json'))->toMediaCollection('project_files', 'nodejs_public_projects_files');
//
//        // tests
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/api/testA01.test.js'))->toMediaCollection('project_tests_api', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/api/testA02.test.js'))->toMediaCollection('project_tests_api', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/api/testA03.test.js'))->toMediaCollection('project_tests_api', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/api/testA04.test.js'))->toMediaCollection('project_tests_api', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/api/testA05.test.js'))->toMediaCollection('project_tests_api', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/web/testA01.test.js'))->toMediaCollection('project_tests_web', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/web/testA02.test.js'))->toMediaCollection('project_tests_web', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/web/testA03.test.js'))->toMediaCollection('project_tests_web', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/web/testA04.test.js'))->toMediaCollection('project_tests_web', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/web/testA05.test.js'))->toMediaCollection('project_tests_web', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/web/images/create-product-page.png'))->toMediaCollection('project_tests_images', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/web/images/error-notFound-page.png'))->toMediaCollection('project_tests_images', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/web/images/index-page.png'))->toMediaCollection('project_tests_images', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/web/images/no-products-found-page.png'))->toMediaCollection('project_tests_images', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/web/images/not-found-product-page.png'))->toMediaCollection('project_tests_images', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/web/images/product-details-page.png'))->toMediaCollection('project_tests_images', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/web/images/products-table-page.png'))->toMediaCollection('project_tests_images', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/tests/web/images/update-product-page.png'))->toMediaCollection('project_tests_images', 'nodejs_public_projects_files');
//
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/api/testB01.test.js'))->toMediaCollection('project_tests_api', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/api/testB02.test.js'))->toMediaCollection('project_tests_api', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/api/testB03.test.js'))->toMediaCollection('project_tests_api', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/api/testB04.test.js'))->toMediaCollection('project_tests_api', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/api/testB05.test.js'))->toMediaCollection('project_tests_api', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/web/testB01.test.js'))->toMediaCollection('project_tests_web', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/web/testB02.test.js'))->toMediaCollection('project_tests_web', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/web/testB03.test.js'))->toMediaCollection('project_tests_web', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/web/testB04.test.js'))->toMediaCollection('project_tests_web', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/web/testB05.test.js'))->toMediaCollection('project_tests_web', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/web/images/edit-page.png'))->toMediaCollection('project_tests_images', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/web/images/edit-password-page.png'))->toMediaCollection('project_tests_images', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/web/images/error-notFound-page.png'))->toMediaCollection('project_tests_images', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/web/images/index-page.png'))->toMediaCollection('project_tests_images', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/web/images/index-page-after-register.png'))->toMediaCollection('project_tests_images', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/web/images/login-page.png'))->toMediaCollection('project_tests_images', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/web/images/login-page-with-error.png'))->toMediaCollection('project_tests_images', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/web/images/profile-page.png'))->toMediaCollection('project_tests_images', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/tests/web/images/register-page.png'))->toMediaCollection('project_tests_images', 'nodejs_public_projects_files');
//
//        // guides
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/guides/Guide A01.pdf'))->toMediaCollection('project_guides', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/guides/Guide A02.pdf'))->toMediaCollection('project_guides', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/guides/Guide A03.pdf'))->toMediaCollection('project_guides', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/guides/Guide A04.pdf'))->toMediaCollection('project_guides', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/guides/Guide A05.pdf'))->toMediaCollection('project_guides', 'nodejs_public_projects_files');
//
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/guides/Guide B01.pdf'))->toMediaCollection('project_guides', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/guides/Guide B02.pdf'))->toMediaCollection('project_guides', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/guides/Guide B03.pdf'))->toMediaCollection('project_guides', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/guides/Guide B04.pdf'))->toMediaCollection('project_guides', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/guides/Guide B05.pdf'))->toMediaCollection('project_guides', 'nodejs_public_projects_files');
//
//        // supplements
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/supplements/.env.example'))->toMediaCollection('project_supplements', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/supplements/.gitignore'))->toMediaCollection('project_supplements', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/supplements/initial_data.json'))->toMediaCollection('project_supplements', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/supplements/main.css'))->toMediaCollection('project_supplements', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/supplements/main.ejs'))->toMediaCollection('project_supplements', 'nodejs_public_projects_files');
//
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/supplements/.env.example'))->toMediaCollection('project_supplements', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/supplements/.gitignore'))->toMediaCollection('project_supplements', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/supplements/main.css'))->toMediaCollection('project_supplements', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/supplements/main.ejs'))->toMediaCollection('project_supplements', 'nodejs_public_projects_files');
//
//        // zips
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/zips/guides.zip'))->toMediaCollection('project_zips', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/zips/supplements.zip'))->toMediaCollection('project_zips', 'nodejs_public_projects_files');
//        $project_api_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/api-experiment/zips/tests.zip'))->toMediaCollection('project_zips', 'nodejs_public_projects_files');
//
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/zips/guides.zip'))->toMediaCollection('project_zips', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/zips/supplements.zip'))->toMediaCollection('project_zips', 'nodejs_public_projects_files');
//        $project_auth_experiment->addMedia(storage_path('app/public/assets/nodejs/projects/auth-experiment/zips/tests.zip'))->toMediaCollection('project_zips', 'nodejs_public_projects_files');

        $api_experiment_project_id = Project::where('title', 'api-experiment')->first()->id;
        $auth_experiment_project_id = Project::where('title', 'auth-experiment')->first()->id;

        // project default file structure
        ProjectsDefaultFileStructure::insert([
            [
                'project_id' => $api_experiment_project_id,
                'structure' => json_encode([
                    'controllers' => [
                        'api' => [
                            'product.controller.js' => '',
                        ],
                        'web' => [
                            'product.controller.js' => '',
                        ],
                    ],
                    'models' => [
                        'product.model.js' => '',
                    ],
                    'node_modules' => '',
                    'routes' => [
                        'api' => [
                            'product.routes.js' => '',
                        ],
                        'web' => [
                            'product.routes.js' => '',
                        ],
                    ],
                    'tests' => [
                        'api' => [
                            'testA01.test.js' => '',
                            'testA02.test.js' => '',
                            'testA03.test.js' => '',
                            'testA04.test.js' => '',
                            'testA05.test.js' => '',
                        ],
                        'web' => [
                            'images' => [
                                'create-product-page.png' => '',
                                'error-notFound-page.png' => '',
                                'index-page.png' => '',
                                'no-products-found-page.png' => '',
                                'not-found-product-page.png' => '',
                                'product-details-page.png' => '',
                                'products-table-page.png' => '',
                                'update-product-page.png' => '',
                            ],
                            'testA01.test.js' => '',
                            'testA02.test.js' => '',
                            'testA03.test.js' => '',
                            'testA04.test.js' => '',
                            'testA05.test.js' => '',
                        ],
                    ],
                    'web' => [
                        'layouts' => [
                            'main.ejs' => '',
                        ],
                        'styles' => [
                            'main.css' => '',
                        ],
                        'views' => [
                            'products' => [
                                'create.ejs' => '',
                                'details.ejs' => '',
                                'index.ejs' => '',
                                'update.ejs' => '',
                            ],
                            'error.ejs' => '',
                            'index.ejs' => '',
                        ],
                    ],
                    '.env' => '',
                    '.env.example' => '',
                    '.gitignore' => '',
                    'app.js' => '',
                    'initial_data.json' => '',
                    'package-lock.json' => '',
                    'package.json' => '',
                    'README' => '',
                    'server.js' => '',
                ]),
                'excluded' => json_encode([
                    'node_modules',
                    'tests',
                    '.env',
                    '.env.example',
                    '.gitignore',
                    'package-lock.json',
                    'initial_data.json',
                    'README',
                ]),
                'replacements' => json_encode([
                    '.env',
                    'tests',
                    'package.json'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $auth_experiment_project_id,
                'structure' => json_encode([
                    'controllers' => [
                        'api' => [
                            'auth.controller.js' => '',
                        ],
                        'web' => [
                            'auth.controller.js' => '',
                        ],
                    ],
                    'helpers' => [
                        'errorhandler.helper.js' => '',
                        'jsonwebtoken.helper.js' => '',
                    ],
                    'models' => [
                        'user.model.js' => '',
                    ],
                    'node_modules' => '',
                    'routes' => [
                        'api' => [
                            'auth.routes.js' => '',
                        ],
                        'web' => [
                            'auth.routes.js' => '',
                        ],
                    ],
                    'services' => [
                        'auth.service.js' => '',
                    ],
                    'tests' => [
                        'api' => [
                            'testB01.test.js' => '',
                            'testB02.test.js' => '',
                            'testB03.test.js' => '',
                            'testB04.test.js' => '',
                            'testB05.test.js' => '',
                        ],
                        'web' => [
                            'images' => [
                                'edit-page.png' => '',
                                'edit-password-page.png' => '',
                                'error-notFound-page.png' => '',
                                'index-page.png' => '',
                                'index-page-after-register.png' => '',
                                'login-page.png' => '',
                                'login-page-with-error.png' => '',
                                'profile-page.png' => '',
                                'register-page.png' => '',
                            ],
                            'testB01.test.js' => '',
                            'testB02.test.js' => '',
                            'testB03.test.js' => '',
                            'testB04.test.js' => '',
                            'testB05.test.js' => '',
                        ],
                    ],
                    'web' => [
                        'layouts' => [
                            'main.ejs' => '',
                        ],
                        'styles' => [
                            'main.css' => '',
                        ],
                        'views' => [
                            'auth' => [
                                'edit.ejs' => '',
                                'login.ejs' => '',
                                'profile.ejs' => '',
                                'register.ejs' => '',
                            ],
                            'error.ejs' => '',
                            'index.ejs' => '',
                        ],
                    ],
                    '.env' => '',
                    '.env.example' => '',
                    '.gitignore' => '',
                    'app.js' => '',
                    'package-lock.json' => '',
                    'package.json' => '',
                    'README' => '',
                    'server.js' => '',
                ]),
                'excluded' => json_encode([
                    'node_modules',
                    'tests',
                    '.env',
                    '.env.example',
                    '.gitignore',
                    'package-lock.json',
                    'README',
                ]),
                'replacements' => json_encode([
                    '.env',
                    'tests',
                    'package.json'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]

        ]);

        // execution steps
        ExecutionStep::insert([
            [
                'name' => 'Clone Repository',
                'commands' => json_encode([
                    'git', 'clone', '{{repoUrl}}', '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Unzip ZIP Files',
                'commands' => json_encode([
                    'unzip', '{{zipFileDir}}', '-d', '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Remove ZIP Files',
                'commands' => json_encode([
                    'rm', '-rf',  '{{zipFileDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Examine Folder Structure',
                'commands' => json_encode([
                    'ls', '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Add .env File',
                'commands' => json_encode([
                    'cp', '-r', '{{envFile}}', '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Replace package.json',
                'commands' => json_encode([
                    'cp', '-r', '{{packageJson}}', '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Copy 'tests' Folder",
                'commands' => json_encode([
                    'cp', '-r', '{{testsDir}}', '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'NPM Install',
                'commands' => json_encode([
                    'npm', 'install', '{{options}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'NPM Run Start',
                'commands' => json_encode([
                    'npm', 'run', 'start',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'NPM Run Tests',
                'commands' => json_encode([
                    'npm', 'run', '{{testFile}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Delete Temp Directory',
                'commands' => json_encode([
                    'rm', '-rf', '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // execution step projects
        $api_experiment_project_id = Project::where('title', 'api-experiment')->first()->id;
        $auth_experiment_project_id = Project::where('title', 'auth-experiment')->first()->id;

        $clone_repo_execution_step_id = ExecutionStep::where('name', ExecutionStep::$CLONE_REPOSITORY)->first()->id;
        $unzip_zip_files_execution_step_id = ExecutionStep::where('name', ExecutionStep::$UNZIP_ZIP_FILES)->first()->id;
        $checking_folder_structure_execution_step_id = ExecutionStep::where('name', ExecutionStep::$EXAMINE_FOLDER_STRUCTURE)->first()->id;
        $add_env_file_execution_step_id = ExecutionStep::where('name', ExecutionStep::$ADD_ENV_FILE)->first()->id;
        $replace_package_json_execution_step_id = ExecutionStep::where('name', ExecutionStep::$REPLACE_PACKAGE_JSON)->first()->id;
        $copy_tests_folder_step_id = ExecutionStep::where('name', ExecutionStep::$COPY_TESTS_FOLDER)->first()->id;
        $npm_install_execution_step_id = ExecutionStep::where('name', ExecutionStep::$NPM_INSTALL)->first()->id;
        $npm_run_start_execution_step_id = ExecutionStep::where('name', ExecutionStep::$NPM_RUN_START)->first()->id;
        $npm_run_tests_execution_step_id = ExecutionStep::where('name', ExecutionStep::$NPM_RUN_TESTS)->first()->id;
        $delete_temp_directory_execution_step_id = ExecutionStep::where('name', ExecutionStep::$DELETE_TEMP_DIRECTORY)->first()->id;

        ProjectExecutionStep::insert([
            [
                'project_id' => $api_experiment_project_id,
                'execution_step_id' => $clone_repo_execution_step_id,
                'order' => 1,
                'variables' => json_encode([
                    '{{repoUrl}}', '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $api_experiment_project_id,
                'execution_step_id' => $unzip_zip_files_execution_step_id,
                'order' => 2,
                'variables' => json_encode([
                    '{{zipFileDir}}', '{{tempDir}}'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $api_experiment_project_id,
                'execution_step_id' => $checking_folder_structure_execution_step_id,
                'order' => 3,
                'variables' => json_encode([
                    '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $api_experiment_project_id,
                'execution_step_id' => $add_env_file_execution_step_id,
                'order' => 4,
                'variables' => json_encode([
                    '{{envFile}}', '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $api_experiment_project_id,
                'execution_step_id' => $replace_package_json_execution_step_id,
                'order' => 5,
                'variables' => json_encode([
                    '{{packageJson}}', '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $api_experiment_project_id,
                'execution_step_id' => $copy_tests_folder_step_id,
                'order' => 6,
                'variables' => json_encode([
                    '{{testsDir}}', '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $api_experiment_project_id,
                'execution_step_id' => $npm_install_execution_step_id,
                'order' => 7,
                'variables' => json_encode([
                    '{{options}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $api_experiment_project_id,
                'execution_step_id' => $npm_run_start_execution_step_id,
                'order' => 8,
                'variables' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $api_experiment_project_id,
                'execution_step_id' => $npm_run_tests_execution_step_id,
                'order' => 9,
                'variables' => json_encode([
                    '{{testFile}}=api-testA01',
                    '{{testFile}}=web-testA01',
                    '{{testFile}}=api-testA02',
                    '{{testFile}}=web-testA02',
                    '{{testFile}}=api-testA03',
                    '{{testFile}}=web-testA03',
                    '{{testFile}}=api-testA04',
                    '{{testFile}}=web-testA04',
                    '{{testFile}}=api-testA05',
                    '{{testFile}}=web-testA05',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $api_experiment_project_id,
                'execution_step_id' => $delete_temp_directory_execution_step_id,
                'order' => 10,
                'variables' => json_encode([
                    '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $auth_experiment_project_id,
                'execution_step_id' => $clone_repo_execution_step_id,
                'order' => 1,
                'variables' => json_encode([
                    '{{repoUrl}}', '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $auth_experiment_project_id,
                'execution_step_id' => $unzip_zip_files_execution_step_id,
                'order' => 2,
                'variables' => json_encode([
                    '{{zipFileDir}}', '{{tempDir}}'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $auth_experiment_project_id,
                'execution_step_id' => $checking_folder_structure_execution_step_id,
                'order' => 3,
                'variables' => json_encode([
                    '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $auth_experiment_project_id,
                'execution_step_id' => $add_env_file_execution_step_id,
                'order' => 4,
                'variables' => json_encode([
                    '{{envFile}}', '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $auth_experiment_project_id,
                'execution_step_id' => $replace_package_json_execution_step_id,
                'order' => 5,
                'variables' => json_encode([
                    '{{packageJson}}', '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $auth_experiment_project_id,
                'execution_step_id' => $copy_tests_folder_step_id,
                'order' => 6,
                'variables' => json_encode([
                    '{{testsDir}}', '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $auth_experiment_project_id,
                'execution_step_id' => $npm_install_execution_step_id,
                'order' => 7,
                'variables' => json_encode([
                    '{{options}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $auth_experiment_project_id,
                'execution_step_id' => $npm_run_start_execution_step_id,
                'order' => 8,
                'variables' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $auth_experiment_project_id,
                'execution_step_id' => $npm_run_tests_execution_step_id,
                'order' => 9,
                'variables' => json_encode([
                    '{{testFile}}=api-testB01',
                    '{{testFile}}=web-testB01',
                    '{{testFile}}=api-testB02',
                    '{{testFile}}=web-testB02',
                    '{{testFile}}=api-testB03',
                    '{{testFile}}=web-testB03',
                    '{{testFile}}=api-testB04',
                    '{{testFile}}=web-testB04',
                    '{{testFile}}=api-testB05',
                    '{{testFile}}=web-testB05',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_id' => $auth_experiment_project_id,
                'execution_step_id' => $delete_temp_directory_execution_step_id,
                'order' => 10,
                'variables' => json_encode([
                    '{{tempDir}}',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
