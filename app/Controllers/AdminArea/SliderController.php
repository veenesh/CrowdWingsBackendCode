<?php

namespace App\Controllers\AdminArea;
use App\Controllers\BaseController;

class SliderController extends BaseController
{
    public function index()
    {
        $sliderModel = new \App\Models\SliderModel();
        $data['sliders'] = $sliderModel->findAll();
        return view('admin/sliders/index', $data);
    }

    public function uploadForm()
    {
        return view('admin/sliders/upload');
    }

    public function uploadImages()
    {
        $files = $this->request->getFiles();

        if ($files && isset($files['images'])) {
            $sliderModel = new \App\Models\SliderModel();

            foreach ($files['images'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/sliders', $newName);

                    $sliderModel->insert([
                        'image' => $newName,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Images uploaded successfully!');
        }

        return redirect()->back()->with('error', 'No images selected or invalid files.');
    }

    public function delete($id)
{
    $sliderModel = new \App\Models\SliderModel();
    $slider = $sliderModel->find($id);

    if ($slider) {
        $filePath = FCPATH . 'uploads/sliders/' . $slider['image'];
        if (is_file($filePath)) {
            unlink($filePath);
        }
        $sliderModel->delete($id);
        return redirect()->back()->with('success', 'Slider deleted successfully.');
    }

    return redirect()->back()->with('error', 'Slider not found.');
}
}
