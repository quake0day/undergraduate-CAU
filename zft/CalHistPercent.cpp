#include "StdAfx.h"
#include "BaseList.h"
#include <math.h>

float cc(float a,float b)
{
	return a/b;
}

void CalHistPercent(long hist[256], float hist_radio[256], float &max_percent)
{
	int i,j;
	long max_per = hist[0];	//�������ֵ����ɨ��ĵ�һ��Ϊ��ֵ
	long all = 0;	//hist���������е����ظ���
	float a=0;


	for( i =0 ; i< 256; i++)
	{
		all = hist[i]+ all;	//ѭ������hist���������е����ظ���
	}

	for( j= 0; j<256; j++)
	{
		if(all > 0)	//���������Ŀ����0
		{
			hist_radio[j] = cc(hist[j],all) * (float)100;	//����ÿ�����ص��Ӧ��ռ�İٷֱȣ����������鱣��
		}
		if (max_per < hist[j])
		{
			max_per = hist[j];	//�������ֵ
		}

	}

	if( all != 0)	
	{
		a = cc(max_per,all);  //�������İٷֱ�
	}		
	else a = 0;

	max_percent = a * (float)100;

}

