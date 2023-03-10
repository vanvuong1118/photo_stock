<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InsertModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class InsertController extends Controller
{
    public function insertform()
    {
        return view('page.insert');
    }
    /*public function insert_data(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:33554432'
        ]);
        $image = $request->file('file');
        $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('/uploads');
        $image->move($destinationPath, $input['imagename']);
        $article = new InsertModel();
        $article->name = $input['imagename'];
        $article->save();
    }*/
    /*public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $request->validate(['image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:33554432']);
            $request->file->store('post', 'public');

            $post = new InsertModel;
            $post->LIBRARYID = $request->LIBRARYID;
            $post->IMAGE = $request->file->hashName();
            $post->TAG = $request->tag;
            $post->Description = $request->description;
            $post->save();
            return redirect()->action('InsertController@

    }*/
    public function store(Request $request)
    {
        //kiem tra gia tri
        $this->validate(
            $request,
            [
                'Library' => 'required',
                'tag' => 'required',
                'description' => 'required'
            ],
            [
                'Library.required' => 'Ban chua chon thu vien',
                'tag.required' => 'Ban chua nhap tag',
                'description.required' => 'Ban chua nhap mo ta'
            ]
        );
        //luu hinh khi co file hinh
        $getImage = '';
        if ($request->hasFile('image'))
        //ham kiem tra du lieu
        {
            $this->validate(
                $request,
                [
                    //Ki???m tra ????ng file ??u??i .jpg,.jpeg,.png.gif v?? dung l?????ng kh??ng qu?? 2M
                    'image' => 'mimes:jpg,jpeg,png,gif|max:30000',
                ],
                [
                    //T??y ch???nh hi???n th??? th??ng b??o kh??ng th??a ??i???u ki???n
                    'image.mimes' => 'Ch??? ch???p nh???n h??nh th??? v???i ??u??i .jpg .jpeg .png .gif',
                    'image.max' => 'H??nh th??? gi???i h???n dung l?????ng kh??ng qu?? 2M',
                ]
            );
            //l??u file v??? local folder
            $image = $request->file('image');
            $getImage = $image->getClientOriginalName();
            $filePath = public_path('post\\' . $image);
            $image->move('upload', $image->getClientOriginalName());
            //@move_uploaded_file($image, $filePath);

            $account = Session::get('Account');
            $dsThongtin = DB::table('profile')->where('UID', '=', $account)->first(['UID', 'NAME', 'GENDER', 'ADDRESS', 'TEL', 'POSTED', 'FOLLOW']);
            $UID = $dsThongtin->UID;

            $dataInsert = array(
                'LIBRARYID' => $request->get('Library'),
                'IMAGE' => $getImage,
                'TAG' => $request->get('tag'),
                'UID' => $UID, //S???a sau
                'Description' => $request->get('description')
            );

            //Insert v??o b???ng post
            $insertData = DB::table('post')->insert($dataInsert);
            if ($insertData) {
                return redirect()->action('LibraryController@index');
            } else {
                echo "<script type='text/javascript'>alert('That Bai');</script>";
                return redirect()->back();
            }
        }
    }
}
