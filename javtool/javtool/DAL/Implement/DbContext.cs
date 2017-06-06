using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Pomelo.Data.MySql;
using javtool.Models;
using Microsoft.Extensions.Options;
using javtool.DAL.Context;
using System.Text.RegularExpressions;
using System.Reflection;
using System.Data;
using System.Collections;

namespace javtool.DAL.Implement
{
    public class DbContext : IDbContext
    {
        private readonly DbSetting _dbSetting;
        public DbContext(IOptions<DbSetting> dbSetting)
        {
            _dbSetting = dbSetting.Value;
        }

        private IList<MySqlParameter> GetParameters(string sqlString, object param)
        {
            var regex = new Regex(@"(\@\w+)", RegexOptions.Compiled | RegexOptions.IgnoreCase);

            var allMatch = regex.Matches(sqlString);

            var results = new List<MySqlParameter>();
            MySqlParameter obj;
            foreach (var match in allMatch)
            {
                if (param == null)
                {
                    obj = new MySqlParameter(match.ToString(), DBNull.Value);
                }
                else
                {
                    var p = param.GetType().GetProperty(match.ToString().TrimStart('@'));
                    if (p != null)
                    {
                        obj = new MySqlParameter(match.ToString(), p.GetValue(param, null));
                    }
                    else
                    {
                        obj = new MySqlParameter(match.ToString(), DBNull.Value);
                    }
                }
                results.Add(obj);
            }

            return results;
        }

        private List<T> MapDataReaderToCollection<T>(IDataReader dr) where T : new()
        {
            List<T> results = new List<T>();

            if (dr == null) return results;

            Type businessEntityType = typeof(T);
            Hashtable hashtable = new Hashtable();
            PropertyInfo[] properties = businessEntityType.GetProperties();
            foreach (PropertyInfo info in properties)
            {
                hashtable[info.Name.ToUpper()] = info;
            }
            while (dr.Read())
            {
                T newObject = new T();
                for (int index = 0; index < dr.FieldCount; index++)
                {
                    PropertyInfo info = (PropertyInfo)
                                        hashtable[dr.GetName(index).ToUpper()];
                    if ((info != null) && info.CanWrite)
                    {
                        info.SetValue(newObject, dr.GetValue(index), null);
                    }
                }
                results.Add(newObject);
            }
            dr.Close();
            return results;
        }

        public async Task<bool> ExecuteNonQuery(string sqlString)
        {
            return await ExecuteNonQuery(sqlString, null);
        }

        public async Task<bool> ExecuteNonQuery(string sqlString, object param)
        {
            var connection = new MySqlConnection(_dbSetting.MySqlConnectionString);
            var command = new MySqlCommand(sqlString, connection);

            await command.Connection.OpenAsync();

            var sqlparams = GetParameters(sqlString, param);
            if (sqlparams.Count > 0) command.Parameters.AddRange(sqlparams.ToArray());

            await command.ExecuteNonQueryAsync();

            command.Connection.Close();

            return true;
        }

        public async Task<IList<T>> GetMultiData<T>(string sqlString) where T : new()
        {
            return await GetMultiData<T>(sqlString, null);
        }

        public async Task<IList<T>> GetMultiData<T>(string sqlString, object param) where T : new()
        {
            var connection = new MySqlConnection(_dbSetting.MySqlConnectionString);
            var command = new MySqlCommand(sqlString, connection);

            await command.Connection.OpenAsync();

            var sqlparams = GetParameters(sqlString, param);
            if (sqlparams.Count > 0) command.Parameters.AddRange(sqlparams.ToArray());

            var reader = await command.ExecuteReaderAsync();

            var results = MapDataReaderToCollection<T>(reader);

            command.Connection.Close();

            return results;
        }

        public async Task<T> GetSingleData<T>(string sqlString) where T : new()
        {
            return await GetSingleData<T>(sqlString, null);
        }

        public async Task<T> GetSingleData<T>(string sqlString, object param) where T : new()
        {
            var results = await GetMultiData<T>(sqlString, param);
            if (results != null & results.Count > 0) return results[0];

            return default(T);
        }
    }
}
