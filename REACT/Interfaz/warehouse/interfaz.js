import React from 'react';
import { StyleSheet, Text, View, useWindowDimensions } from 'react-native';

export default function interfaz_warehouse() {
  const { width } = useWindowDimensions();

  return (
    <View style={styles.container}>
      <View style={styles.row}>
        <Text style={[styles.labelText, { backgroundColor: 'rgb(42, 126, 209)' }]} onPress={() => alert('Hola 1')}>Almacenes</Text>
        <Text style={[styles.labelText, { backgroundColor: 'rgb(209, 53, 42)' }]} onPress={() => alert('Hola 1')}>Editar Almacen</Text>
      </View>
      <View style={styles.row}>
        <Text style={[styles.labelText, { backgroundColor: 'rgb(42, 126, 209)' }]} onPress={() => alert('Hola 1')}>Hola</Text>
        <Text style={[styles.labelText, { backgroundColor: 'rgb(209, 53, 42)' }]} onPress={() => alert('Hola 1')}>Hola</Text>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: 'rgb(33, 37, 41)',
    padding: 20,
  },
  labelText: {
    height: 165,  // Cambié de "150px" a 150, porque en React Native no usa unidades como px.
    width: 165,   // Lo mismo aquí.
    borderRadius: 30,
    justifyContent: 'center',
    alignItems: 'center',
    display: 'flex',
    textAlign: 'center',  // Para centrar el texto dentro del Text
    lineHeight: 150,  // Para centrar el texto verticalmente en el Text
    fontSize: "24pt"
  },
  row: {
    flexDirection: 'row',  // Cambié a 'row' para que los elementos estén en fila.
    justifyContent: 'center',
    alignItems: 'center',
    gap: 30,  // Opcional: Para agregar espacio entre los elementos en la fila
    marginBottom: 30,
    marginTop: 10
  }
});
