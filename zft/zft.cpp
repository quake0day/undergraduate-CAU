// zft.cpp : implementation file
//

#include "stdafx.h"
#include "DIPAX.h"
#include "zft.h"
#include "Global.h"
#include "BaseList.h"


#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif



/////////////////////////////////////////////////////////////////////////////
// Czft dialog


Czft::Czft(CWnd* pParent /*=NULL*/)
	: CDialog(Czft::IDD, pParent)
{
	//{{AFX_DATA_INIT(Czft)
		// NOTE: the ClassWizard will add member initialization here
		//m_fn = 1.0f;
		//m_nHistType = 0;
	//}}AFX_DATA_INIT
	m_pParent = pParent;
	m_pImage_in = NULL;
	m_pImage_out = NULL;
}


void Czft::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(Czft)
		// NOTE: the ClassWizard will add DDX and DDV calls here
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(Czft, CDialog)
	//{{AFX_MSG_MAP(Czft)
	ON_BN_CLICKED(ID_zft, Onzft)
	ON_BN_CLICKED(IDC_BUTTON1, OnButton1)
	ON_BN_CLICKED(IDC_BUTTON2, OnButton2)
	ON_BN_CLICKED(IDC_BUTTON3, OnButton3)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// Czft message handlers

BOOL Czft::OnInitDialog() 
{
	CDialog::OnInitDialog();

	//�軭�ƶ���
	m_shift_val = 30;
	m_left = 0; //��
	m_top = 0; //��
	m_right = 200; //��
	m_bottom = 100; //��
	
	//������ݱ�ʾ
	m_bAxisText = TRUE;

	//���ͼ��ߴ�
	m_nxsize = ::GetXSize();
	m_nysize = ::GetYSize();
	if(::GetImageType() == 24)
		return FALSE;


	m_pImage_in = new BYTE[m_nxsize*m_nysize];
	m_pImage_out = new BYTE[m_nxsize* m_nysize];
	m_pImage_hist = new BYTE[m_nxsize*m_nysize];

	::ReadImageData(m_pImage_in);

	::Histgram(m_pImage_in,m_nxsize,m_nysize, m_hist);
	m_create = 1;
	m_bflge = FALSE;
	
	// TODO: Add extra initialization here
	
	return TRUE;  // return TRUE unless you set the focus to a control
	              // EXCEPTION: OCX Property Pages should return FALSE
}

void Czft::OnPaint()
{
	CPaintDC dc(this);

	CWnd * pWnd = GetDlgItem(IDC_HIST_STATIC);

	pWnd->Invalidate();
	pWnd->UpdateWindow();

	UpdateData(TRUE);

	DispHistogram();

}

void Czft::DispHistogram()
{
	CDC* pDC;
	CRect rect;
	CWnd* pWnd = GetDlgItem(IDC_HIST_STATIC);
	pWnd->GetClientRect( &rect );

	//��
	m_left = rect.left + 2;

	//��
	m_top = rect.top + 8;

	//�� 
	m_right = rect.right - 2;

	//��
	m_bottom = rect.bottom -2;

	m_shift_val = 30;

	//�����Դ�Ĺ�ϵ
	pDC = pWnd -> GetDC();

	//ȷ�����ڵĲ���
	UpdateData(TRUE);

	//��ƽ��������ݼ���ٷֱ�
//	if(m_nHistType == 1)
//		::CalHistPercent(m_histSmooth, m_disp_array, m_max_percent);
	//��ԭ���ݼ���ٷֱ�
//	else
	CalHistPercent(m_hist, m_disp_array, m_max_percent);

	
	

	//�趨y�����ֵ
	m_max_value_y = (int)(m_max_percent + 1);

	//��Χ��Ϊ 0 -100
	if( m_max_value_y >= 100) {m_max_value_y = 100;}
	if( m_max_value_y <= 0) {m_max_value_y = 0;}


	//�������軭�����趨
	short ret = DrawHistArea(pDC);

	if(ret == OK)
	{
		//x��y����軭
		DrawAxisLine(pDC);

		//x��y�����ֵı�ʾ
		DrawAxisText(pDC);

		//����ֵ�ı�ʾ
		DrawGrayValueMono(pDC);

	}
	//��Դ��ϵ�Ľ��
	pWnd -> ReleaseDC(pDC);

}

short Czft::DrawHistArea(CDC* pDC)
{
	int i, max_value,min_value;

	//x��
	//�ڲ��Ŵ���
	//n����ʾ��׼��

	m_inzoom_x = (float) 1;

	//x���С�̶�
	m_small_x = (float)5;

	//x��Ĵ�̶�
	m_large_x = (float)25;

	//x�����ֱ�ʾ�̶ȵı仯��
	m_text_delta_x = 25;
	//x������ֵ
	m_max_value_x = 255;

	//y��
	//5 10 25 50 100
	max_value = 100;
	min_value = 100/2;

	//��������
	for( i = 0; i < 5; i++)
	{
		if( (min_value < m_max_value_y) && (m_max_value_y <= max_value) ){
		break;
		}
		else 
		{
			max_value = min_value;
			if(min_value < 5) {	min_value =2 ; break;}
			if(min_value == 25) min_value = 10;
			else min_value = min_value /2 ;
		}
	}

	m_max_value_y = max_value;

	m_text_delta_y = m_max_value_y / 5;
	if(m_text_delta_y == 0) m_text_delta_y = 1;
	m_large_y = (float) m_text_delta_y;
	m_small_y = m_large_y / (float)10;

	//�趨�ڲ�������
	if( m_max_value_y == 0 ) m_inzoom_y = (float)1;

	//�ڲ�������
	else m_inzoom_y = 100 / (float)m_max_value_y;

	//��������Ŀ�ȡ��߶�
	m_resize_x = (float) (m_right - m_left);
	m_resize_y = (float) (m_bottom - m_top);

	if( m_resize_x < 200) return (NG);
	if( m_shift_val < 30) return (NG);

	//y����
	if( m_resize_y < 100) return (NG);

	//��׼��x
	m_zoom_x = (m_resize_x - (float)60) / (float)280;

	//��׼��y
	m_zoom_y = m_resize_y / (float) 120;

	//���������軭
	CPen Pen(PS_SOLID,1, RGB(255,255,255));
	CPen* pOldPen = pDC->SelectObject(&Pen);
	pDC->Rectangle(m_left,m_bottom + 1, m_left + (int)m_resize_x + 1, m_bottom - (int)m_resize_y);
	pDC->SelectObject(pOldPen);

	return (OK);

}

//x��y�Ŀ̶����ֱ�ʾ
void Czft::DrawAxisText( CDC* pDC)
{
	int i;
	float old_posi_y,new_posi_y;
	float old_posi_x,new_posi_x;

	CString str_x, str_y;

	pDC->SetTextAlign( TA_LEFT | TA_BOTTOM);
	pDC->SetTextColor( RGB(0,0,0));

	//����ӵ��ʱ���������ʾ
	 if( m_text_delta_y !=0)
	 {
		 old_posi_y = (float)( m_bottom -20);
		 //y�Ŀ̶����ֱ�ʾ
		 str_y.Format("%3s", "0 %");

		 //"0 %" ���ֵ�΢����
		 pDC->TextOut(m_left+17, (int)old_posi_y, str_y, 3);

		 for( i =1 ; i<(int)(m_max_value_y / m_text_delta_y+1); i++)
		 {
			 new_posi_y = (float) m_bottom - (float)20 - (m_text_delta_y * (float)i * m_zoom_y * m_inzoom_y);

			 

			 //y�����ּ�Ŀ��
			 //m_text_interval_y =16

			 if( (old_posi_y - (float)16) > new_posi_y)
			 {
				 //y��̶�����
				 str_y.Format("%3d", m_text_delta_y*i);
				 pDC->TextOut( m_left+3, (int)new_posi_y, str_y,3);
				 old_posi_y = new_posi_y;
			 }
		 }
	 }

	 if( m_text_delta_x != 0)
	 {
		 old_posi_x = (float)(m_left+m_shift_val*2 - 15);

		 //x��̶�����
		 str_x.Format("%3d", m_text_delta_x*0);
		 pDC->TextOut( (int) old_posi_x, m_bottom-5, str_x, 3);

		 for( i=1; i<(int)(m_max_value_x / m_text_delta_x +1); i++)
		 {
			 new_posi_x = (float)m_left+(float)m_shift_val*2+(m_text_delta_x * (float)i * m_zoom_x * m_inzoom_x) - (float)15;
			 //x�����ּ��

			 if((new_posi_x - old_posi_x) >(float)28)
			 {
				 //x��̶���ֵ
				 str_x.Format("%3d", m_text_delta_x * i);
				 pDC->TextOut( (int) new_posi_x, m_bottom -5 , str_x, 3);
				 old_posi_x = new_posi_x;
			 }
		 }
	 }
}

void Czft::DrawAxisLine(CDC* pDC)
{
	//x���軭
	DrawAxisXline(pDC);
	//y���軭
	DrawAxisYLine(pDC);
	//�������軭
	DrawSubLine(pDC);
}

void Czft::DrawAxisXline(CDC* pDC)
{
	int i;
	CString str_x;

	//�ߵķ���
	CPen Pen(PS_SOLID,1,RGB(0,0,0));
	CPen* pOldPen = pDC-> SelectObject(&Pen);

	//x����
	//x��
	 pDC->MoveTo(m_left+m_shift_val * 2, m_bottom - m_shift_val);
	 pDC->LineTo(m_left+m_shift_val * 2 + (int)(m_max_value_x * m_zoom_x * m_inzoom_x), m_bottom-m_shift_val);
	 //С�̶�
	 for( i = 0; i<(int)( m_max_value_x / m_small_x+1); i++)
	 {
		 //x��̶�
		 pDC->MoveTo(m_left+m_shift_val * 2 + (int)(m_small_x * (float)i * m_zoom_x * m_inzoom_x), m_bottom - m_shift_val);
		 pDC->LineTo(m_left+m_shift_val * 2 + (int)(m_small_x * (float)i * m_zoom_x * m_inzoom_x), m_bottom - m_shift_val + 2);

	 }
	 //��̶�
	 for( i = 0; i<(int) (m_max_value_x / m_large_x +1); i++)
	 {
		 //x��̶�
		 pDC->MoveTo(m_left+m_shift_val * 2 + (int)(m_large_x * (float)i* m_zoom_x * m_inzoom_x), m_bottom-m_shift_val);
		 pDC->LineTo(m_left+m_shift_val * 2 + (int)(m_large_x * (float)i* m_zoom_x * m_inzoom_x), m_bottom-m_shift_val+4);

	 }

	 pDC->SelectObject(pOldPen);
}

//y���軭

void Czft::DrawAxisYLine(CDC* pDC)
{
	int i;
	CString str_y;
	//�ߵķ���
	CPen Pen(PS_SOLID,1,RGB(0,0,0));
	CPen* pOldPen = pDC-> SelectObject(&Pen);

	//y����
	//y����

	pDC->MoveTo(m_left + m_shift_val*2, m_bottom- m_shift_val);
	pDC->LineTo(m_left + m_shift_val*2, m_bottom - m_shift_val - (int)((float)m_max_value_y * m_zoom_y * m_inzoom_y));

	//С�̶�
	for( i = 0; i<(int)(m_max_value_y / m_small_y + 1); i++)
	{
		pDC->MoveTo(m_left + m_shift_val * 2, m_bottom - m_shift_val - (int)(m_small_y * (float)i * m_zoom_y * m_inzoom_y));
		pDC->LineTo(m_left + m_shift_val * 2-2, m_bottom - m_shift_val - (int)(m_small_y * (float)i * m_zoom_y * m_inzoom_y));
	}

	//��̶�
	for( i = 0; i<(int)(m_max_value_y / m_large_y +1); i++)
	{
		//y��̶�
		pDC->MoveTo(m_left + m_shift_val * 2 ,m_bottom - m_shift_val - (int)(m_large_y * (float)i * m_zoom_y * m_inzoom_y));
		pDC->LineTo(m_left + m_shift_val * 2 -4,m_bottom - m_shift_val - (int)(m_large_y * (float)i * m_zoom_y * m_inzoom_y));
	}
	pDC->SelectObject(pOldPen);

}

void Czft::DrawSubLine(CDC* pDC)
{
	int st_x,st_y,end_x,end_y,i;
	//�ߵķ���
	CPen Pen(PS_SOLID,1,RGB(0,0,0));
	CPen* pOldPen = pDC-> SelectObject(&Pen);

	//x��
	pDC->MoveTo(m_left+m_shift_val * 2, m_bottom - m_shift_val);
	pDC->LineTo(m_left+m_shift_val * 2+ (int)(m_max_value_x * m_zoom_x * m_inzoom_x), m_bottom - m_shift_val);
	for( i = 1; i<6; i++)
	{
		st_x = m_left+m_shift_val*2;
		st_y = m_bottom-m_shift_val - (int)(m_large_y * (float)i* m_zoom_y* m_inzoom_y);
		end_x = m_left+m_shift_val*2+(int)(m_max_value_x*m_zoom_x*m_inzoom_x);
		end_y = m_bottom-m_shift_val-(int)(m_large_y*(float)i*m_zoom_y*m_inzoom_y);
		pDC->MoveTo(st_x,st_y);
		pDC->LineTo(end_x,end_y);

	}

	pDC->SelectObject(pOldPen);

}

void Czft::DrawGrayValueMono( CDC* pDC)
{
	int i,st_x,st_gray;
	CPen PenD(PS_SOLID, 1, RGB(255,0,0));
	CPen* pOldPen = pDC->SelectObject(&PenD);

	UpdateData(TRUE);

	for( i = 0; i<=255; i++)
	{
		pDC->SelectObject(&PenD);
		st_x = m_left+ m_shift_val*2 + (int)((float)i*m_zoom_x*m_inzoom_x);
		st_gray = (int)(m_disp_array[i] * m_zoom_y * m_inzoom_y);
		pDC->MoveTo(st_x,m_bottom-m_shift_val);
		pDC->LineTo(st_x,m_bottom-m_shift_val - st_gray);
	}

	PenD.DeleteObject();
	pDC->SelectObject(pOldPen);

}

void Czft::OnHistOriginal()
{
	::Disp_image(m_pImage_in);

	::Histgram(m_pImage_in, m_nxsize, m_nysize, m_hist);

	m_pParent->Invalidate();

	DispHistogram();
}


void Czft::OnButton2() 
{
	::Disp_image(m_pImage_in);
    m_pParent->Invalidate();
}


