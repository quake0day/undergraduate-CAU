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

	//描画移动量
	m_shift_val = 30;
	m_left = 0; //左
	m_top = 0; //上
	m_right = 200; //右
	m_bottom = 100; //下
	
	//轴的数据表示
	m_bAxisText = TRUE;

	//获得图像尺寸
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

	//左
	m_left = rect.left + 2;

	//上
	m_top = rect.top + 8;

	//右 
	m_right = rect.right - 2;

	//下
	m_bottom = rect.bottom -2;

	m_shift_val = 30;

	//获得资源的关系
	pDC = pWnd -> GetDC();

	//确定窗口的参数
	UpdateData(TRUE);

	//用平滑后的数据计算百分比
//	if(m_nHistType == 1)
//		::CalHistPercent(m_histSmooth, m_disp_array, m_max_percent);
	//用原数据计算百分比
//	else
	CalHistPercent(m_hist, m_disp_array, m_max_percent);

	
	

	//设定y轴最大值
	m_max_value_y = (int)(m_max_percent + 1);

	//范围定为 0 -100
	if( m_max_value_y >= 100) {m_max_value_y = 100;}
	if( m_max_value_y <= 0) {m_max_value_y = 0;}


	//参数、描画区域设定
	short ret = DrawHistArea(pDC);

	if(ret == OK)
	{
		//x、y轴的描画
		DrawAxisLine(pDC);

		//x、y轴文字的表示
		DrawAxisText(pDC);

		//亮度值的表示
		DrawGrayValueMono(pDC);

	}
	//资源关系的解放
	pWnd -> ReleaseDC(pDC);

}

short Czft::DrawHistArea(CDC* pDC)
{
	int i, max_value,min_value;

	//x轴
	//内部放大率
	//n倍表示基准幅

	m_inzoom_x = (float) 1;

	//x轴的小刻度
	m_small_x = (float)5;

	//x轴的大刻度
	m_large_x = (float)25;

	//x轴文字表示刻度的变化量
	m_text_delta_x = 25;
	//x轴的最大值
	m_max_value_x = 255;

	//y轴
	//5 10 25 50 100
	max_value = 100;
	min_value = 100/2;

	//求最大比例
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

	//设定内部扩大率
	if( m_max_value_y == 0 ) m_inzoom_y = (float)1;

	//内部扩大率
	else m_inzoom_y = 100 / (float)m_max_value_y;

	//右上领域的宽度、高度
	m_resize_x = (float) (m_right - m_left);
	m_resize_y = (float) (m_bottom - m_top);

	if( m_resize_x < 200) return (NG);
	if( m_shift_val < 30) return (NG);

	//y界限
	if( m_resize_y < 100) return (NG);

	//基准长x
	m_zoom_x = (m_resize_x - (float)60) / (float)280;

	//基准长y
	m_zoom_y = m_resize_y / (float) 120;

	//矩形区域描画
	CPen Pen(PS_SOLID,1, RGB(255,255,255));
	CPen* pOldPen = pDC->SelectObject(&Pen);
	pDC->Rectangle(m_left,m_bottom + 1, m_left + (int)m_resize_x + 1, m_bottom - (int)m_resize_y);
	pDC->SelectObject(pOldPen);

	return (OK);

}

//x、y的刻度文字表示
void Czft::DrawAxisText( CDC* pDC)
{
	int i;
	float old_posi_y,new_posi_y;
	float old_posi_x,new_posi_x;

	CString str_x, str_y;

	pDC->SetTextAlign( TA_LEFT | TA_BOTTOM);
	pDC->SetTextColor( RGB(0,0,0));

	//文字拥挤时拉开距离表示
	 if( m_text_delta_y !=0)
	 {
		 old_posi_y = (float)( m_bottom -20);
		 //y的刻度文字表示
		 str_y.Format("%3s", "0 %");

		 //"0 %" 文字的微调整
		 pDC->TextOut(m_left+17, (int)old_posi_y, str_y, 3);

		 for( i =1 ; i<(int)(m_max_value_y / m_text_delta_y+1); i++)
		 {
			 new_posi_y = (float) m_bottom - (float)20 - (m_text_delta_y * (float)i * m_zoom_y * m_inzoom_y);

			 

			 //y轴数字间的宽度
			 //m_text_interval_y =16

			 if( (old_posi_y - (float)16) > new_posi_y)
			 {
				 //y轴刻度数字
				 str_y.Format("%3d", m_text_delta_y*i);
				 pDC->TextOut( m_left+3, (int)new_posi_y, str_y,3);
				 old_posi_y = new_posi_y;
			 }
		 }
	 }

	 if( m_text_delta_x != 0)
	 {
		 old_posi_x = (float)(m_left+m_shift_val*2 - 15);

		 //x轴刻度数字
		 str_x.Format("%3d", m_text_delta_x*0);
		 pDC->TextOut( (int) old_posi_x, m_bottom-5, str_x, 3);

		 for( i=1; i<(int)(m_max_value_x / m_text_delta_x +1); i++)
		 {
			 new_posi_x = (float)m_left+(float)m_shift_val*2+(m_text_delta_x * (float)i * m_zoom_x * m_inzoom_x) - (float)15;
			 //x轴文字间隔

			 if((new_posi_x - old_posi_x) >(float)28)
			 {
				 //x轴刻度数值
				 str_x.Format("%3d", m_text_delta_x * i);
				 pDC->TextOut( (int) new_posi_x, m_bottom -5 , str_x, 3);
				 old_posi_x = new_posi_x;
			 }
		 }
	 }
}

void Czft::DrawAxisLine(CDC* pDC)
{
	//x轴描画
	DrawAxisXline(pDC);
	//y轴描画
	DrawAxisYLine(pDC);
	//辅助线描画
	DrawSubLine(pDC);
}

void Czft::DrawAxisXline(CDC* pDC)
{
	int i;
	CString str_x;

	//线的幅宽
	CPen Pen(PS_SOLID,1,RGB(0,0,0));
	CPen* pOldPen = pDC-> SelectObject(&Pen);

	//x坐标
	//x轴
	 pDC->MoveTo(m_left+m_shift_val * 2, m_bottom - m_shift_val);
	 pDC->LineTo(m_left+m_shift_val * 2 + (int)(m_max_value_x * m_zoom_x * m_inzoom_x), m_bottom-m_shift_val);
	 //小刻度
	 for( i = 0; i<(int)( m_max_value_x / m_small_x+1); i++)
	 {
		 //x轴刻度
		 pDC->MoveTo(m_left+m_shift_val * 2 + (int)(m_small_x * (float)i * m_zoom_x * m_inzoom_x), m_bottom - m_shift_val);
		 pDC->LineTo(m_left+m_shift_val * 2 + (int)(m_small_x * (float)i * m_zoom_x * m_inzoom_x), m_bottom - m_shift_val + 2);

	 }
	 //大刻度
	 for( i = 0; i<(int) (m_max_value_x / m_large_x +1); i++)
	 {
		 //x轴刻度
		 pDC->MoveTo(m_left+m_shift_val * 2 + (int)(m_large_x * (float)i* m_zoom_x * m_inzoom_x), m_bottom-m_shift_val);
		 pDC->LineTo(m_left+m_shift_val * 2 + (int)(m_large_x * (float)i* m_zoom_x * m_inzoom_x), m_bottom-m_shift_val+4);

	 }

	 pDC->SelectObject(pOldPen);
}

//y轴描画

void Czft::DrawAxisYLine(CDC* pDC)
{
	int i;
	CString str_y;
	//线的幅宽
	CPen Pen(PS_SOLID,1,RGB(0,0,0));
	CPen* pOldPen = pDC-> SelectObject(&Pen);

	//y坐标
	//y轴左

	pDC->MoveTo(m_left + m_shift_val*2, m_bottom- m_shift_val);
	pDC->LineTo(m_left + m_shift_val*2, m_bottom - m_shift_val - (int)((float)m_max_value_y * m_zoom_y * m_inzoom_y));

	//小刻度
	for( i = 0; i<(int)(m_max_value_y / m_small_y + 1); i++)
	{
		pDC->MoveTo(m_left + m_shift_val * 2, m_bottom - m_shift_val - (int)(m_small_y * (float)i * m_zoom_y * m_inzoom_y));
		pDC->LineTo(m_left + m_shift_val * 2-2, m_bottom - m_shift_val - (int)(m_small_y * (float)i * m_zoom_y * m_inzoom_y));
	}

	//大刻度
	for( i = 0; i<(int)(m_max_value_y / m_large_y +1); i++)
	{
		//y轴刻度
		pDC->MoveTo(m_left + m_shift_val * 2 ,m_bottom - m_shift_val - (int)(m_large_y * (float)i * m_zoom_y * m_inzoom_y));
		pDC->LineTo(m_left + m_shift_val * 2 -4,m_bottom - m_shift_val - (int)(m_large_y * (float)i * m_zoom_y * m_inzoom_y));
	}
	pDC->SelectObject(pOldPen);

}

void Czft::DrawSubLine(CDC* pDC)
{
	int st_x,st_y,end_x,end_y,i;
	//线的幅宽
	CPen Pen(PS_SOLID,1,RGB(0,0,0));
	CPen* pOldPen = pDC-> SelectObject(&Pen);

	//x轴
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


