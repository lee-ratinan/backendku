<?php

namespace App\Controllers;

use App\Models\ProfileIdentityModel;

class Profile extends BaseController
{

    const PERMISSION_REQUIRED = 'profile';

    /**
     * @return string
     */
    public function index(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $model   = new ProfileIdentityModel();
        $data    = [
            'page_title'       => 'Profile',
            'slug'             => 'profile',
            'user_session'     => $session->user,
            'roles'            => $session->roles,
            'current_role'     => $session->current_role,
            'document_types'   => $model->getDocumentTypes(),
            'documents'        => $model->findAll()
        ];
        return view('profile_data', $data);
    }

    /**
     * @return string
     */
    public function resume(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $data    = [
            'page_title'       => 'Resume',
            'slug'             => 'resume',
            'user_session'     => $session->user,
            'roles'            => $session->roles,
            'current_role'     => $session->current_role
        ];
        return view('profile_resume', $data);
    }

    /**
     * @return string
     */
    public function resumeBuilder(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $data    = [
            'page_title'       => 'Resume Builder',
            'slug'             => 'resume',
            'user_session'     => $session->user,
            'roles'            => $session->roles,
            'current_role'     => $session->current_role
        ];
        return view('profile_resume_builder', $data);
    }

    /**
     * @return string
     */
    public function resumeCoverLetter(): string
    {
        if (PERMISSION_NOT_PERMITTED == retrieve_permission_for_user(self::PERMISSION_REQUIRED)) {
            return permission_denied();
        }
        $session = session();
        $data    = [
            'page_title'       => 'Resume Builder',
            'slug'             => 'resume',
            'user_session'     => $session->user,
            'roles'            => $session->roles,
            'current_role'     => $session->current_role
        ];
        return view('profile_cover_letter', $data);
    }
}