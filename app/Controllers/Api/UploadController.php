<?php

namespace App\Controllers\Api;
use App\Models\MemberModel;
use CodeIgniter\RESTful\ResourceController;


class UploadController extends ResourceController
{
    public function uploadProfilePic()
    {
        $MEMBER = new MemberModel();
        $member_id = $_SESSION['member_id'];
        $id = $_SESSION['id'];
        $file = $this->request->getFile('file'); // Retrieve the uploaded file

    $allowedTypes = ['image/jpeg', 'image/png']; // Allow JPEG and PNG

    if ($file->isValid() && in_array($file->getClientMimeType(), $allowedTypes)) {
        $newName = $file->getRandomName(); // Generate a random name for the file

        if ($file->move('uploads', $newName)) {
            $MEMBER->update($id, [
                'image'=>$newName,
                ]);
            return $this->response->setJSON(['message' => 'File uploaded successfully']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'File could not be uploaded']);
        }
        
        
        
    } else {
        return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid file or file type not allowed']);
    }
    }
}
