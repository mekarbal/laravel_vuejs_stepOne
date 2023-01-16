<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Add new company
     * body {
     *  title:string,
     *  location:string
     * }
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
        ]);
        $company_alreadyExists = Company::where(
            'name',
            '=',
            $request->get('name')
        )->first();

        if ($company_alreadyExists !== null) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'This company already exists',
                ],
                402
            );
        }

        $company = Company::create($data);
        return response()->json(
            [
                'status' => true,
                'message' => 'Company created Successfully',
                'data' => $company,
            ],
            201
        );
    }
    /**
     * GET all Companies
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $companies = Company::all();

        if ($companies->isEmpty()) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'There is no company registred yet',
                ],
                404
            );
        }

        return response()->json(
            [
                'status' => true,
                'message' => 'All Companies',
                'data' => $companies,
            ],
            200
        );
    }

    /**
     * Summary of getOneCompany
     * get One company by id
     * @param Request $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOneCompany(Request $request, $id)
    {
        $company = Company::find($id);

        if ($company === null) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Company Not Found',
                ],
                404
            );
        }

        return response()->json(
            [
                'status' => true,
                'message' => 'Get one company successfully',
                'data' => $company,
            ],
            200
        );
    }
    /**
     * Summary of deleteCompany
     *
     * delete comapany by id
     * @param Request $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCompany(Request $request, $id)
    {
        $company = Company::find($id);

        if ($company == null) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Company Not Found',
                ],
                404
            );
        }
        $company->delete();
        return response()->json(
            [
                'status' => true,
                'message' => 'Company deleted Successfully',
            ],
            200
        );
    }

    /**
     * Summary of updateCompany
     *
     * update One Company by id
     * body{
     *
     * }
     * @param Request $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCompany(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'nullable|string',
            'location' => 'nullable|string',
        ]);
        $company = Company::find($id);

        if ($company == null) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Company Not Found',
                ],
                404
            );
        }

        $company->update($data);
        return response()->json(
            [
                'status' => true,
                'message' => 'Company updated Successfully',
                'data' => $company,
            ],
            200
        );
    }

    /**
          * Summary of searchCompany
          search company by name
          * @param Request $request
          * @param mixed $name
          * @return \Illuminate\Http\JsonResponse
          */

    public function searchCompany(Request $request, $name)
    {
        $company = Company::query()
            ->where('name', 'LIKE', "%{$name}%")
            ->get();

        if ($company->isEmpty()) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Search Not Found',
                ],
                404
            );
        }
        return response()->json(
            [
                'status' => true,
                'message' => 'Search Result',
                'data' => $company,
            ],
            200
        );
    }
    public function sortCompanyByName()
    {
        $companies = Company::orderBy('name')->get();

        return response()->json(
            [
                'status' => true,
                'message' => 'Search Result',
                'data' => $companies,
            ],
            200
        );
    }
}
