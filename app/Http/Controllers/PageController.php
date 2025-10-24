<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    /**
     * عرض صفحة التواصل
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * معالجة وإرسال رسالة التواصل مع Transaction
     */
    public function sendContact(Request $request)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:1000'
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.max' => 'الاسم يجب ألا يتجاوز 255 حرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.max' => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرف',
            'phone.max' => 'رقم الهاتف يجب ألا يتجاوز 20 حرف',
            'subject.required' => 'الموضوع مطلوب',
            'subject.max' => 'الموضوع يجب ألا يتجاوز 255 حرف',
            'message.required' => 'الرسالة مطلوبة',
            'message.min' => 'الرسالة يجب أن تكون 10 أحرف على الأقل',
            'message.max' => 'الرسالة يجب ألا تتجاوز 1000 حرف'
        ]);

        try {
            // استخدام Transaction للتأكد من نجاح جميع العمليات
            DB::beginTransaction();

            // حفظ الرسالة في قاعدة البيانات
            $contact = Contact::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // تسجيل العملية في الـ Log
            Log::info('تم استلام رسالة تواصل جديدة', [
                'contact_id' => $contact->id,
                'name' => $contact->name,
                'email' => $contact->email,
                'subject' => $contact->subject,
            ]);

            // إرسال إشعار بالبريد الإلكتروني (اختياري)
            // Mail::to('admin@example.com')->send(new ContactNotification($contact));

            // إرسال رسالة شكر للمستخدم (اختياري)
            // Mail::to($contact->email)->send(new ContactThankYou($contact));

            // تأكيد Transaction
            DB::commit();

            // رسالة نجاح مع تأثيرات
            return back()->with('success', '✨ تم إرسال رسالتك بنجاح! سنتواصل معك قريباً');

        } catch (\Exception $e) {
            // التراجع عن جميع العمليات في حالة حدوث خطأ
            DB::rollBack();

            // تسجيل الخطأ
            Log::error('فشل في حفظ رسالة التواصل', [
                'error' => $e->getMessage(),
                'email' => $request->email ?? 'غير معروف',
            ]);

            // رسالة خطأ للمستخدم
            return back()
                ->withInput()
                ->withErrors(['error' => '⚠️ عذراً، حدث خطأ أثناء إرسال رسالتك. يرجى المحاولة مرة أخرى.']);
        }
    }

    /**
     * عرض جميع رسائل التواصل (للإدارة فقط)
     */
    public function viewContacts()
    {
        $contacts = Contact::latest()->paginate(20);
        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * تحديد رسالة كمقروءة
     */
    public function markAsRead($id)
    {
        try {
            DB::beginTransaction();

            $contact = Contact::findOrFail($id);
            $contact->update(['is_read' => true]);

            DB::commit();

            return back()->with('success', 'تم تحديد الرسالة كمقروءة');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('فشل في تحديث حالة الرسالة', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'حدث خطأ أثناء التحديث']);
        }
    }

    /**
     * حذف رسالة
     */
    public function deleteContact($id)
    {
        try {
            DB::beginTransaction();

            $contact = Contact::findOrFail($id);
            $contact->delete();

            DB::commit();

            Log::info('تم حذف رسالة تواصل', ['contact_id' => $id]);

            return back()->with('success', 'تم حذف الرسالة بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('فشل في حذف الرسالة', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'حدث خطأ أثناء الحذف']);
        }
    }
}
