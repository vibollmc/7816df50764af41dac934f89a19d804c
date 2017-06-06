using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace javtool.DAL.Context
{
    interface IDbContext
    {
        Task<bool> ExecuteNonQuery(string sqlString);
        Task<bool> ExecuteNonQuery(string sqlString, Object param);

        Task<T> GetSingleData<T>(string sqlString) where T : new();
        Task<T> GetSingleData<T>(string sqlString, Object param) where T : new();

        Task<IList<T>> GetMultiData<T>(string sqlString) where T : new();
        Task<IList<T>> GetMultiData<T>(string sqlString, Object param) where T : new();
    }
}
