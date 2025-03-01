<?php

namespace App\Actions\Ministere2LaVerite;

use App\DTOs\medias\MediasDTO;
use App\Models\Assembly;
use App\Models\Media;
use App\Models\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class StoreMediaAction
{
    public function execute(MediasDTO $mediaDTO): void
    {
        foreach ($mediaDTO->files as $file) {
            if ($file instanceof UploadedFile) {

                $name = $file->getClientOriginalName();
                $type = $file->getMimeType();

                $filepath = $file->store('medias/images', 'public');
                $uri = asset('storage/' . $filepath);

                $media = Media::create([
                    'name' => $name,
                    'uri' => $uri,
                    'comment' => $mediaDTO->comment,
                    'type' => $type,
                    'user_id' => $mediaDTO->sendToAssemblies ? null : $mediaDTO->userId,
                    'sender_id' => auth()->id(),
                ]);

                if ($mediaDTO->sendToAssemblies) {
                    // Send files to assemblies
                    $assemblies = Assembly::whereIn('id', $mediaDTO->assemblies)->get();
                    $media->assemblies()->attach($assemblies);
                }
            }
        }

        session()->flash('success', 'Media created successfully');
    }
}

//try {
//
//    \DB::beginTransaction();
//
//    // 1- Create the medias and store them
//    if ($request->hasFile('media')) {
//        $file = $request->file('media');
//        $filepath = $file->store('medias/images', 'public');
//        $uri = asset('storage/' . $filepath);
//        $name = $file->getFilename();
//        $type = $file->getType();
//
//        $media = Media::create([
//            'name' => $name,
//            'uri' => $uri,
//            'type' => $type,
//        ]);
//
//        if (!$request->input('sendToAssembly')) {
//            $user = User::findOrFail($request->input('user'));
//            $media->update([
//                'user_id' => $user->id,
//            ]);
//        } else {
//            $assemblies = Assembly::whereIn('id', $request->input('assemblies'))->get();
//            $media->assemblies()->attach($assemblies);
//        }
//    }
//    \DB::commit();
//} catch (\Exception $e) {
//    \DB::rollBack();
//}
