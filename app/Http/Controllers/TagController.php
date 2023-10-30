<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show(): JsonResponse
    {
        $tags = Tag::all();

        return response()->json(['tags' => $tags]);
    }

    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate(['name' => ['required', 'unique:tags,name', 'regex:/^[a-zA-Z]+$/']], ['name.unique' => 'این برچسب قبلا ثبت شده است', 'name.regex' => 'برچسب وارد شده نامعتبر است']);

        $tag = Tag::create(['name' => $validated['name']]);

        return response()->json(['newTag' => $tag]);
    }
    public function edit(Request $request): JsonResponse
    {
        $validated = $request->validate(['name' => '']);

        $tag = Tag::find($request->get('id'));
        $tag->update(['name' => $validated['name']]);

        return response()->json([]);
    }

    public function delete($id): JsonResponse
    {
        $tag = Tag::find($id);
        $tag->delete();

        return response()->jsone([]);
    }
}
